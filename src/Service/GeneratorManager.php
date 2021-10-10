<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
 *
 * This file is part of Shadow Daemon. Shadow Daemon is free software: you can
 * redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Service;

use App\Entity\WhitelistRule;
use App\Entity\WhitelistFilter;
use App\Entity\BlacklistRule;
use App\Entity\BlacklistFilter;
use App\Entity\IntegrityRule;
use App\Entity\Hash;
use App\Entity\Parameter;
use App\Entity\Statistic;

class GeneratorManager
{
    private $em;
    private $filters;
    private $statistics;
    private $rules;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    private function determineWhitelistFilter($input)
    {
        if (!isset($this->filters['whitelist'])) {
            $this->filters['whitelist'] = $this->em->getRepository('SwdAnalyzerBundle:WhitelistFilter')->findAllOrderedByImpact()->getResult();
        }

        foreach ($this->filters['whitelist'] as $filter) {
            /* Escape the delimiter. */
            $rule = str_replace('~', '\~', $filter->getRule());

            /* Return the first filter that matches. */
            if (preg_match('~' . $rule . '~is', $input)) {
                return $filter;
            }
        }

        /* The last filter ("everything") should match in any case. */
        throw new Exception('The whitelist filter table seems to be damaged.');
    }

    private function splitPath($path)
    {
        return preg_split('/\\\\.(*SKIP)(*FAIL)|\|/s', $path);
    }

    private function unifyArray($path)
    {
        $pathes = $this->splitPath($path);

        /* A path with 2 parts is not an array. */
        if (count($pathes) === 2) {
            return $path;
        }

        /* Replace last item with asterisk. */
        array_pop($pathes);
        array_push($pathes, '*');

        /* Return new path as string. */
        return implode('|', $pathes);
    }

    private function normalizeCaller($path, $caller)
    {
        /* SERVER and COOKIE input should be valid for all callers. */
        if (preg_match('/^(SERVER|COOKIE)\|/', $path)) {
            return '*';
        } else {
            return $caller;
        }
    }

    private function generateWhitelistStatistics($settings)
    {
        /* Get all parameters that were recorded in learning mode. */
        $parameters = $this->em->getRepository('SwdAnalyzerBundle:Parameter')->findAllLearningBySettings($settings)->getResult();

        foreach ($parameters as $parameter) {
            $request = $parameter->getRequest();

            /* Determine caller and path for statistics. */
            if ($settings->getUnifyWhitelistCallers()) {
                $path = '*';
            } else {
                $path = ($settings->getUnifyWhitelistArrays() ?
                    $this->unifyArray($parameter->getPath()) :
                    $parameter->getPath()
                );
            }

            $caller = $this->normalizeCaller($path, $request->getCaller());

            /* Get existing stat object for this (caller, path) or create a new one. */
            $stats = (isset($this->statistics['whitelist'][$caller][$path]) ?
                $this->statistics['whitelist'][$caller][$path] :
                new Statistic()
            );

            $stats->addLength(strlen($parameter->getValue()));
            $stats->addWhitelistFilter($this->determineWhitelistFilter($parameter->getValue()));

            /* Increase the counter. This has to happen last. */
            $stats->increaseCounter($request->getClientIP());

            $this->statistics['whitelist'][$caller][$path] = $stats;
        }
    }

    private function generateWhitelistRules($settings)
    {
        if (!$this->statistics['whitelist']) {
            return;
        }

        foreach ($this->statistics['whitelist'] as $caller => $caller_value) {
            foreach ($caller_value as $path => $stats) {
                /* Ignore slips. */
                if ($stats->getUniqueCounter() < $settings->getMinUniqueVisitors()) {
                    continue;
                }

                /* Create a new rule. */
                $rule = new WhitelistRule();
                $rule->setProfile($settings->getProfile());
                $rule->setPath($path);
                $rule->setCaller($caller);
                $rule->setStatus($settings->getStatus());
                $rule->setDate(new \DateTime());

                /**
                 * If the variance is near zero we can use the average length as min and max length.
                 * If the variance is a bit higher but not really high we can calculate a max length.
                 * If the variance is too high it is not possible to set a min or max length.
                 */
                if ($stats->getLengthVariance() < ($settings->getMaxLengthVariance() * 0.2)) {
                    $rule->setMinLength(ceil($stats->getAverageLength()));
                    $rule->setMaxLength(ceil($stats->getAverageLength()));
                } elseif ($stats->getLengthVariance() < $settings->getMaxLengthVariance()) {
                    $rule->setMinLength($stats->getMinLength());
                    $rule->setMaxLength($stats->getMaxLength());
                } else {
                    $rule->setMinLength(-1);
                    $rule->setMaxLength(-1);
                }

                /**
                 * Next we determine the filter. If almost every request used the same filter we can take that filter.
                 * If this is not the case and there is no clear "winner" we have to take the "everything" filter.
                 */
                $filter = $stats->getDominantWhitelistFilter($settings->getMinFilterDominance());

                if ($filter === false) {
                    $everything = $this->em->getRepository('SwdAnalyzerBundle:WhitelistFilter')->findHighestImpact()->getSingleResult();
                    $rule->setFilter($everything);
                } else {
                    $rule->setFilter($filter);
                }

                /* Save rule in class attribute. */
                $this->rules['whitelist'][] = $rule;
            }
        }
    }

    private function generateBlacklistStatistics($settings)
    {
        /* Get all parameters that were recorded in learning mode. */
        $parameters = $this->em->getRepository('SwdAnalyzerBundle:Parameter')->findAllLearningBySettings($settings)->getResult();

        foreach ($parameters as $parameter) {
            $request = $parameter->getRequest();

            /* Determine caller and path for statistics. */
            if ($settings->getUnifyBlacklistCallers()) {
                $path = '*';
            } else {
                $path = ($settings->getUnifyBlacklistArrays() ?
                    $this->unifyArray($parameter->getPath()) :
                    $parameter->getPath()
                );
            }

            $caller = $this->normalizeCaller($path, $request->getCaller());

            /* Get existing stat object for this (caller, path) or create a new one. */
            $stats = (isset($this->statistics['blacklist'][$caller][$path]) ?
                $this->statistics['blacklist'][$caller][$path] :
                new Statistic()
            );

            /* Calculate and add total impact. */
            $totalImpact = 0;

            $filters = $parameter->getMatchingBlacklistFilters();

            foreach ($filters as $filter) {
                $totalImpact += $filter->getImpact();
            }

            $stats->addTotalImpact($totalImpact);

            /* Increase the counter. */
            $stats->increaseCounter($request->getClientIP());

            $this->statistics['blacklist'][$caller][$path] = $stats;
        }
    }

    private function generateBlacklistRules($settings)
    {
        if (!$this->statistics['blacklist']) {
            return;
        }

        foreach ($this->statistics['blacklist'] as $caller => $caller_value) {
            foreach ($caller_value as $path => $stats) {
                /* Ignore slips. */
                if ($stats->getUniqueCounter() < $settings->getMinUniqueVisitors()) {
                    continue;
                }

                /* Find parameters that always seem to have the same total impact. */
                $threshold = $stats->getDominantBlacklistImpact($settings->getMinThresholdDominance());

                if ($threshold !== false) {
                    /* Don't add rule if the threshold is below the global threshold. */
                    if ($threshold <= $settings->getProfile()->getBlacklistThreshold()) {
                        continue;
                    }

                    /* Create a new rule. */
                    $rule = new BlacklistRule();
                    $rule->setProfile($settings->getProfile());
                    $rule->setPath($path);
                    $rule->setCaller($caller);
                    $rule->setStatus($settings->getStatus());
                    $rule->setDate(new \DateTime());
                    $rule->setThreshold($threshold);

                    /* Save rule in class attribute. */
                    $this->rules['blacklist'][] = $rule;
                }
            }
        }
    }

    private function generateIntegrityStatistics($settings)
    {
        /* Get all requests that were recorded in learning mode. */
        $requests = $this->em->getRepository('SwdAnalyzerBundle:Request')->findAllLearningBySettings($settings)->getResult();

        foreach ($requests as $request) {
            $caller = $request->getCaller();

            /* Get existing stat object for this caller or create a new one. */
            $stats = (isset($this->statistics['integrity'][$caller]) ?
                $this->statistics['integrity'][$caller] :
                new Statistic()
            );

            /* Add the hashes to the statistic object. */
            $hashes = $request->getHashes();

            foreach ($hashes as $hash) {
                $stats->addHash($hash->getAlgorithm(), $hash->getDigest());
            }

            /* Increase the counter. */
            $stats->increaseCounter($request->getClientIP());

            $this->statistics['integrity'][$caller] = $stats;
        }
    }

    private function generateIntegritytRules($settings)
    {
        if (!$this->statistics['integrity']) {
            return;
        }

        foreach ($this->statistics['integrity'] as $caller => $stats) {
            /* Ignore slips. */
            if ($stats->getUniqueCounter() < $settings->getMinUniqueVisitors()) {
                continue;
            }

            $hashes = $stats->getHashes();

            foreach ($hashes as $algorithm => $digest) {
                /* Create a new rule. */
                $rule = new IntegrityRule();
                $rule->setProfile($settings->getProfile());
                $rule->setCaller($caller);
                $rule->setStatus($settings->getStatus());
                $rule->setDate(new \DateTime());
                $rule->setAlgorithm($algorithm);
                $rule->setDigest($digest);

                /* Save rule in class attribute. */
                $this->rules['integrity'][] = $rule;
            }
        }
    }

    public function start($settings)
    {
        if ($settings->getEnableWhitelist()) {
            $this->generateWhitelistStatistics($settings);
            $this->generateWhitelistRules($settings);
        }

        if ($settings->getEnableBlacklist()) {
            $this->generateBlacklistStatistics($settings);
            $this->generateBlacklistRules($settings);
        }

        if ($settings->getEnableIntegrity()) {
            $this->generateIntegrityStatistics($settings);
            $this->generateIntegritytRules($settings);
        }
    }

    private function persistRules($id, $repo)
    {
        if (!isset($this->rules[$id])) {
            return 0;
        }

        $counter = 0;

        foreach ($this->rules[$id] as $rule) {
            /* Do not continue if the same rule is already in the database. */
            $existingRules = $this->em->getRepository($repo)->findAllByRule($rule)->getResult();

            if ($existingRules) {
                continue;
            }

            /* Mark rule for storage. */
            $this->em->persist($rule);

            $counter++;
        }

        /* Flush the objects so that they get stored in the database. */
        $this->em->flush();

        return $counter;
    }

    public function save()
    {
        return $this->persistRules('whitelist', 'SwdAnalyzerBundle:WhitelistRule')
            + $this->persistRules('blacklist', 'SwdAnalyzerBundle:BlacklistRule')
            + $this->persistRules('integrity', 'SwdAnalyzerBundle:IntegrityRule');
    }
}
