<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2015 Hendrik Buchwald <hb@zecure.org>
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

namespace Swd\AnalyzerBundle\Generator;

use Swd\AnalyzerBundle\Entity\WhitelistRule;
use Swd\AnalyzerBundle\Entity\WhitelistFilter;
use Swd\AnalyzerBundle\Entity\BlacklistRule;
use Swd\AnalyzerBundle\Entity\BlacklistFilter;
use Swd\AnalyzerBundle\Entity\Parameter;
use Swd\AnalyzerBundle\Entity\ParameterStatistic;

class GeneratorManager
{
	private $filters;
	private $statistics;
	private $rules;

	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
		$this->filters = $this->em->getRepository('SwdAnalyzerBundle:WhitelistFilter')->findAllOrderedByImpact()->getResult();
	}

	private function determineFilter($input)
	{
		foreach ($this->filters as $filter)
		{
			/* Escape the delimiter. */
			$rule = str_replace('~', '\~', $filter->getRule());

			/* Return the first filter that matches. */
			if (preg_match('~' . $rule . '~is', $input))
			{
				return $filter;
			}
		}

		/* The last filter ("everything") should match in any case. */
		throw new Exception('The whitelist filter table seems to be damaged.');
	}

	private function splitPath($path) {
		return preg_split('/\\\\.(*SKIP)(*FAIL)|\|/s', $path);
	}

	public function unifyArray($path)
	{
		$pathes = $this->splitPath($path);

		/* A path with 2 parts is not an array. */
		if (count($pathes) === 2)
		{
			return $path;
		}

		/* Replace last item with asterisk. */
		array_pop($pathes);
		array_push($pathes, '*');

		/* Return new path as string. */
		return implode('|', $pathes);
	}

	public function normalizeCaller($path, $caller)
	{
		/* SERVER and COOKIE input should be valid for all callers. */
		if (preg_match('/^(SERVER|COOKIE)\|/', $path))
		{
			return '*';
		}
		else
		{
			return $caller;
		}
	}

	public function generateStatistics($settings)
	{
		/* Get all parameters that were recorded in learning mode and are not classified as blacklist threat. */
		$parameters = $this->em->getRepository('SwdAnalyzerBundle:Parameter')->findAllLearningBySettings($settings)->getResult();

		foreach ($parameters as $parameter)
		{
			$request = $parameter->getRequest();

			/* Determine caller and path for whitelist statistics. */
			if ($settings->getUnifyWhitelistCallers())
			{
				$path['whitelist'] = '*';
			}
			else
			{
				$path['whitelist'] = ($settings->getUnifyWhitelistArrays() ?
					$this->unifyArray($parameter->getPath()) :
					$parameter->getPath()
				);
			}

			$caller['whitelist'] = $this->normalizeCaller($path['whitelist'], $request->getCaller());

			/* Determine caller and path for blacklist statistics. */
			if ($settings->getUnifyBlacklistCallers())
			{
				$path['blacklist'] = '*';
			}
			else
			{
				$path['blacklist'] = ($settings->getUnifyBlacklistArrays() ?
					$this->unifyArray($parameter->getPath()) :
					$parameter->getPath()
				);
			}

			$caller['blacklist'] = $this->normalizeCaller($path['blacklist'], $request->getCaller());

			/* Get existing stat object for this (caller, path) or create a new one. */
			$stats['whitelist'] = (isset($this->statistics[$caller][$path['whitelist']]) ?
				$this->statistics[$caller][$path['whitelist']] :
				new ParameterStatistic()
			);

			$stats['whitelist']->addLength(strlen($parameter->getValue()));
			$stats['whitelist']->addFilter($this->determineFilter($parameter->getValue()));

			/* Increase the counters. This has to happen last. */
			$stats['whitelist']->increaseCounter($request->getClientIP());

			$this->statistics[$caller['whitelist']][$path['whitelist']] = $stats['whitelist'];
		}
	}

	public function generateRules($settings)
	{
		if (!$this->statistics)
		{
			return;
		}

		foreach ($this->statistics as $caller => $caller_value)
		{
			foreach ($caller_value as $path => $stats)
			{
				/* Ignore slips. */
				if ($stats->getUniqueCounter() < $settings->getMinUniqueVisitors())
				{
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
				if ($stats->getLengthVariance() < ($settings->getMaxLengthVariance() * 0.2))
				{
					$rule->setMinLength(ceil($stats->getAverageLength()));
					$rule->setMaxLength(ceil($stats->getAverageLength()));
				}
				elseif ($stats->getLengthVariance() < $settings->getMaxLengthVariance())
				{
					$rule->setMinLength($stats->getMinLength());
					$rule->setMaxLength($stats->getMaxLength());
				}
				else
				{
					$rule->setMinLength(-1);
					$rule->setMaxLength(-1);
				}

				/**
				 * Next we determine the filter. If almost every request used the same filter we can take that filter.
				 * If this is not the case and there is no clear "winner" we have to take the "everything" filter.
				 */
				$filter = $stats->getDominantFilter($settings->getMinFilterDominance());

				if ($filter === false)
				{
					$everything = $this->em->getRepository('SwdAnalyzerBundle:WhitelistFilter')->findHighestImpact()->getSingleResult();
					$rule->setFilter($everything);
				}
				else
				{
					$rule->setFilter($filter);
				}

				/* Save rule in class attribute. */
				$this->rules[] = $rule;
			}
		}
	}

	public function persistRules()
	{
		$counter = 0;

		if (!$this->rules)
		{
			return 0;
		}

		foreach ($this->rules as $rule)
		{
			/* Do not continue if the same rule is already in the database. */
			$existingRules = $this->em->getRepository('SwdAnalyzerBundle:WhitelistRule')->findAllByRule($rule)->getResult();

			if ($existingRules)
			{
				continue;
			}

			/* Persist the rule. */
			$this->em->persist($rule);

			/* Increase counter for user feedback. */
			$counter++;
		}

		/* Flush the objects (not down the toilet). */
		$this->em->flush();

		return $counter;
	}
}
