<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2022 Hendrik Buchwald <hb@zecure.org>
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

namespace Swd\AnalyzerBundle\Entity;

/**
 * Statistic
 */
class Statistic
{
    /**
     * @var integer
     */
    private $counter;

    /**
     * @var array
     */
    private $uniqueCounter;

    /**
     * @var integer
     */
    private $averageLength;

    /**
     * @var integer
     */
    private $lengthVariance;

    /**
     * @var integer
     */
    private $minLength;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * @var array
     */
    private $totalImpacts;

    /**
     * @var array
     */
    private $whitelistFilters;

    /**
     * @var array
     */
    private $hashes;


    public function increaseCounter($clientIp)
    {
        $this->counter++;
        $this->uniqueCounter[$clientIp] = 1;
    }

    public function getUniqueCounter()
    {
        return count($this->uniqueCounter);
    }

    public function addLength($length)
    {
        if (!$this->minLength || ($length < $this->minLength)) {
            $this->minLength = $length;
        }

        if (!$this->maxLength || ($length > $this->maxLength)) {
            $this->maxLength = $length;
        }

        $this->averageLength = (($this->averageLength * $this->counter) + $length) / ($this->counter + 1);
        $this->lengthVariance = sqrt(((pow($this->lengthVariance, 2) * $this->counter) + pow(($length - $this->averageLength), 2)) / ($this->counter + 1));
    }

    public function addTotalImpact($totalImpact)
    {
        if (!isset($this->totalImpacts[$totalImpact])) {
            $this->totalImpacts[$totalImpact]['counter'] = 1;
        } else {
            $this->totalImpacts[$totalImpact]['counter']++;
        }
    }

    public function getAverageLength()
    {
        return $this->averageLength;
    }

    public function getLengthVariance()
    {
        return $this->lengthVariance;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function addWhitelistFilter($filter)
    {
        if (!isset($this->whitelistFilters[$filter->getId()])) {
            $this->whitelistFilters[$filter->getId()]['object'] = $filter;
            $this->whitelistFilters[$filter->getId()]['counter'] = 1;
        } else {
            $this->whitelistFilters[$filter->getId()]['counter']++;
        }
    }

    /**
     * Returns the the dominant filter or false if there is none.
     */
    public function getDominantWhitelistFilter($minFilterDominance)
    {
        $sum = 0;

        foreach ($this->whitelistFilters as $filter) {
            $sum += $filter['counter'];
        }

        foreach ($this->whitelistFilters as $filter) {
            if (($filter['counter'] / $sum) >= ($minFilterDominance / 100)) {
                return $filter['object'];
            }
        }

        return false;
    }

    /**
     * Returns the the dominant impact or false if there is none.
     */
    public function getDominantBlacklistImpact($minThresholdDominance)
    {
        $sum = 0;

        foreach ($this->totalImpacts as $impact) {
            $sum += $impact['counter'];
        }

        foreach ($this->totalImpacts as $key => $impact) {
            if (($impact['counter'] / $sum) >= ($minThresholdDominance / 100)) {
                return $key;
            }
        }

        return false;
    }

    public function addHash($algorithm, $digest)
    {
        $this->hashes[$algorithm] = $digest;
    }

    public function getHashes()
    {
        return $this->hashes;
    }
}
