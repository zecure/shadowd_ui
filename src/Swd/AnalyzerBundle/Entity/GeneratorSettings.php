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

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GeneratorSettings
 */
class GeneratorSettings
{
	/**
	 * @var entity
	 *
	 * @Assert\NotBlank()
	 */
	private $profile;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThan(
	 *	 value = 0
	 * )
	 */
	private $minUniqueVisitors = 100;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThan(
	 *	 value = 0
	 * )
	 * @Assert\LessThanOrEqual(
	 *	 value = 100
	 * )
	 */
	private $minFilterDominance = 90;

	/**
	 * @var \ArrayCollection
	 */
	private $searchPaths;

	/**
	 * @var boolean
	 */
	private $unifyArrays;


	public function __construct()
	{
		$this->searchPaths = new ArrayCollection();
		$this->unifyArrays = true;
	}

	public function setProfile(\Swd\AnalyzerBundle\Entity\Profile $profile = null)
	{
		$this->profile = $profile;

		return $this;
	}

	public function getProfile()
	{
		return $this->profile;
	}

	public function setMinUniqueVisitors($minUniqueVisitors)
	{
		$this->minUniqueVisitors = $minUniqueVisitors;

		return $this;
	}

	public function getMinUniqueVisitors()
	{
		return $this->minUniqueVisitors;
	}

	public function setMinFilterDominance($minFilterDominance)
	{
		$this->minFilterDominance = $minFilterDominance;

		return $this;
	}

	public function getMinFilterDominance()
	{
		return $this->minFilterDominance;
	}

	public function addSearchPath($path)
	{
		$this->searchPaths[] = $path;

		return $this;
	}

	public function getSearchPaths()
	{
		return $this->searchPaths;
	}

	public function setUnifyArrays($unifyArrays)
	{
		$this->unifyArrays = $unifyArrays;

		return $this;
	}

	public function getUnifyArrays()
	{
		return $this->unifyArrays;
	}
}
