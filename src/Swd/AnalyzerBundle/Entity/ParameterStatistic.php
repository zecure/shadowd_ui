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

namespace Swd\AnalyzerBundle\Entity;

/**
 * ParameterStatistic
 */
class ParameterStatistic
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
	private $filters;


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
		if (!$this->minLength || ($length < $this->minLength))
		{
			$this->minLength = $length;
		}

		if (!$this->maxLength || ($length > $this->maxLength))
		{
			$this->maxLength = $length;
		}

		$this->averageLength = (($this->averageLength * $this->counter) + $length) / ($this->counter + 1);
		$this->lengthVariance = sqrt(((pow($this->lengthVariance, 2) * $this->counter) + pow(($length - $this->averageLength), 2)) / ($this->counter + 1));
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

	public function addFilter($filter)
	{
		if (!isset($this->filters[$filter->getId()]))
		{
			$this->filters[$filter->getId()]['object'] = $filter;
			$this->filters[$filter->getId()]['counter'] = 1;
		}
		else
		{
			$this->filters[$filter->getId()]['counter']++;
		}
	}

	/**
	 * Returns the the dominant filter or false if there is none. 
	 */
	public function getDominantFilter($minFilterDominance)
	{
		$sum = 0;

		foreach ($this->filters as $filter)
		{
			$sum += $filter['counter'];
		}

		foreach ($this->filters as $filter)
		{
			if (($filter['counter'] / $sum) > ($minFilterDominance / 100))
			{
				return $filter['object'];
			}
		}

		return false;
	}
}
