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
	 */
	private $predefined = 2;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 */
	private $status = 3;

	/**
	 * @var boolean
	 */
	private $enableWhitelist;

	/**
	 * @var boolean
	 */
	private $enableBlacklist;

	/**
	 * @var boolean
	 */
	private $enableIntegrity;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThan(
	 *	 value = 0
	 * )
	 */
	private $minUniqueVisitors = 20;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThanOrEqual(
	 *	 value = 0
	 * )
	 */
	private $maxLengthVariance;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThanOrEqual(
	 *	 value = 0
	 * )
	 * @Assert\LessThanOrEqual(
	 *	 value = 100
	 * )
	 */
	private $minFilterDominance;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank()
	 * @Assert\GreaterThanOrEqual(
	 *	 value = 0
	 * )
	 * @Assert\LessThanOrEqual(
	 *	 value = 100
	 * )
	 */
	private $minThresholdDominance;

	/**
	 * @var boolean
	 */
	private $unifyWhitelistArrays;

	/**
	 * @var boolean
	 */
	private $unifyWhitelistCallers;

	/**
	 * @var boolean
	 */
	private $unifyBlacklistArrays;

	/**
	 * @var boolean
	 */
	private $unifyBlacklistCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $includeCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $includePaths;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $excludePaths;


	public function __construct()
	{
		$this->includeCallers = new ArrayCollection();
		$this->includePaths = new ArrayCollection();
		$this->excludeCallers = new ArrayCollection();
		$this->excludePaths = new ArrayCollection();
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

	public function setPredefined($predefined)
	{
		$this->predefined = $predefined;

		return $this;
	}

	public function getPredefined()
	{
		return $this->predefined;
	}

	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setEnableWhitelist($enableWhitelist)
	{
		$this->enableWhitelist = $enableWhitelist;

		return $this;
	}

	public function getEnableWhitelist()
	{
		return $this->enableWhitelist;
	}

	public function setEnableIntegrity($enableIntegrity)
	{
		$this->enableIntegrity = $enableIntegrity;

		return $this;
	}

	public function getEnableIntegrity()
	{
		return $this->enableIntegrity;
	}

	public function setEnableBlacklist($enableBlacklist)
	{
		$this->enableBlacklist = $enableBlacklist;

		return $this;
	}

	public function getEnableBlacklist()
	{
		return $this->enableBlacklist;
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

	public function setMaxLengthVariance($maxLengthVariance)
	{
		$this->maxLengthVariance = $maxLengthVariance;

		return $this;
	}

	public function getMaxLengthVariance()
	{
		return $this->maxLengthVariance;
	}

	public function setMinThresholdDominance($minThresholdDominance)
	{
		$this->minThresholdDominance = $minThresholdDominance;

		return $this;
	}

	public function getMinThresholdDominance()
	{
		return $this->minThresholdDominance;
	}

	public function setUnifyWhitelistArrays($unifyArrays)
	{
		$this->unifyWhitelistArrays = $unifyArrays;

		return $this;
	}

	public function getUnifyWhitelistArrays()
	{
		return $this->unifyWhitelistArrays;
	}

	public function setUnifyWhitelistCallers($unifyCallers)
	{
		$this->unifyWhitelistCallers = $unifyCallers;

		return $this;
	}

	public function getUnifyWhitelistCallers()
	{
		return $this->unifyWhitelistCallers;
	}

	public function setUnifyBlacklistArrays($unifyArrays)
	{
		$this->unifyBlacklistArrays = $unifyArrays;

		return $this;
	}

	public function getUnifyBlacklistArrays()
	{
		return $this->unifyBlacklistArrays;
	}

	public function setUnifyBlacklistCallers($unifyCallers)
	{
		$this->unifyBlacklistCallers = $unifyCallers;

		return $this;
	}

	public function getUnifyBlacklistCallers()
	{
		return $this->unifyBlacklistCallers;
	}

	public function addIncludeCaller($caller)
	{
		$this->includeCallers[] = $caller;

		return $this;
	}

	public function getIncludeCallers()
	{
		return $this->includeCallers;
	}

	public function addIncludePath($path)
	{
		$this->includePaths[] = $path;

		return $this;
	}

	public function getIncludePaths()
	{
		return $this->includePaths;
	}

	public function addExcludeCaller($caller)
	{
		$this->excludeCallers[] = $caller;

		return $this;
	}

	public function getExcludeCallers()
	{
		return $this->excludeCallers;
	}

	public function addExcludePath($path)
	{
		$this->excludePaths[] = $path;

		return $this;
	}

	public function getExcludePaths()
	{
		return $this->excludePaths;
	}
}
