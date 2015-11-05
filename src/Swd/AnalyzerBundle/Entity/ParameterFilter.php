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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ParameterFilter
 */
class ParameterFilter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var \ArrayCollection
	 */
	private $includeParameterIds;

	/**
	 * @var \ArrayCollection
	 */
	private $includeProfileIds;

	/**
	 * @var \ArrayCollection
	 */
	private $includeRequestIds;

	/**
	 * @var \ArrayCollection
	 */
	private $includeCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $includeClientIPs;

	/**
	 * @var \DateTime
	 */
	private $includeDateStart;

	/**
	 * @var \DateTime
	 */
	private $includeDateEnd;

	/**
	 * @var \ArrayCollection
	 */
	private $includePaths;

	/**
	 * @var \ArrayCollection
	 */
	private $includeValues;

	/**
	 * @var boolean
	 */
	private $includeThreat;

	/**
	 * @var boolean
	 */
	private $includeNoWhitelistRule;

	/**
	 * @var boolean
	 */
	private $includeBrokenWhitelistRule;

	/**
	 * @var boolean
	 */
	private $includeCriticalImpact;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeParameterIds;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeProfileIds;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeRequestIds;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeClientIPs;

	/**
	 * @var \DateTime
	 */
	private $excludeDateStart;

	/**
	 * @var \DateTime
	 */
	private $excludeDateEnd;

	/**
	 * @var \ArrayCollection
	 */
	private $excludePaths;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeValues;

	/**
	 * @var boolean
	 */
	private $excludeThreat;

	/**
	 * @var boolean
	 */
	private $excludeNoWhitelistRule;

	/**
	 * @var boolean
	 */
	private $excludeBrokenWhitelistRule;

	/**
	 * @var boolean
	 */
	private $excludeCriticalImpact;


	public function __construct()
	{
		$this->includeParameterIds = new ArrayCollection();
		$this->includeProfileIds = new ArrayCollection();
		$this->includeRequestIds = new ArrayCollection();
		$this->includeCallers = new ArrayCollection();
		$this->includeClientIPs = new ArrayCollection();
		$this->includePaths = new ArrayCollection();
		$this->includeValues = new ArrayCollection();
		$this->excludeParameterIds = new ArrayCollection();
		$this->excludeProfileIds = new ArrayCollection();
		$this->excludeRequestIds = new ArrayCollection();
		$this->excludeCallers = new ArrayCollection();
		$this->excludeClientIPs = new ArrayCollection();
		$this->excludePaths = new ArrayCollection();
		$this->excludeValues = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function addIncludeParameterId($parameterId)
	{
		$this->includeParameterIds[] = $parameterId;

		return $this;
	}

	public function getIncludeParameterIds()
	{
		return $this->includeParameterIds;
	}

	public function addIncludeProfileId($profileId)
	{
		$this->includeProfileIds[] = $profileId;

		return $this;
	}

	public function getIncludeProfileIds()
	{
		return $this->includeProfileIds;
	}

	public function addIncludeRequestId($requestId)
	{
		$this->includeRequestIds[] = $requestId;

		return $this;
	}

	public function getIncludeRequestIds()
	{
		return $this->includeRequestIds;
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

	public function addIncludeClientIP($clientIP)
	{
		$this->includeClientIPs[] = $clientIP;

		return $this;
	}

	public function getIncludeClientIPs()
	{
		return $this->includeClientIPs;
	}


	public function setIncludeDateStart($dateStart)
	{
		$this->includeDateStart = $dateStart;

		return $this;
	}

	public function getIncludeDateStart()
	{
		return $this->includeDateStart;
	}

	public function setIncludeDateEnd($dateEnd)
	{
		$this->includeDateEnd = $dateEnd;

		return $this;
	}

	public function getIncludeDateEnd()
	{
		return $this->includeDateEnd;
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

	public function addIncludeValue($value)
	{
		$this->includeValues[] = $value;

		return $this;
	}

	public function getIncludeValues()
	{
		return $this->includeValues;
	}

	public function setIncludeThreat($threat)
	{
		$this->includeThreat = $threat;

		return $this;
	}

	public function getIncludeThreat()
	{
		return $this->includeThreat;
	}

	public function setIncludeNoWhitelistRule($noRule)
	{
		$this->includeNoWhitelistRule = $noRule;

		return $this;
	}

	public function getIncludeNoWhitelistRule()
	{
		return $this->includeNoWhitelistRule;
	}

	public function setIncludeBrokenWhitelistRule($brokenRule)
	{
		$this->includeBrokenWhitelistRule = $brokenRule;

		return $this;
	}

	public function getIncludeBrokenWhitelistRule()
	{
		return $this->includeBrokenWhitelistRule;
	}

	public function setIncludeCriticalImpact($criticalImpact)
	{
		$this->includeCriticalImpact = $criticalImpact;

		return $this;
	}

	public function getIncludeCriticalImpact()
	{
		return $this->includeCriticalImpact;
	}

	public function addExcludeParameterId($parameterId)
	{
		$this->excludeParameterIds[] = $parameterId;

		return $this;
	}

	public function getExcludeParameterIds()
	{
		return $this->excludeParameterIds;
	}

	public function addExcludeProfileId($profileId)
	{
		$this->excludeProfileIds[] = $profileId;

		return $this;
	}

	public function getExcludeProfileIds()
	{
		return $this->excludeProfileIds;
	}

	public function addExcludeRequestId($requestId)
	{
		$this->excludeRequestIds[] = $requestId;

		return $this;
	}

	public function getExcludeRequestIds()
	{
		return $this->excludeRequestIds;
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

	public function addExcludeClientIP($clientIP)
	{
		$this->excludeClientIPs[] = $clientIP;

		return $this;
	}

	public function getExcludeClientIPs()
	{
		return $this->excludeClientIPs;
	}


	public function setExcludeDateStart($dateStart)
	{
		$this->excludeDateStart = $dateStart;

		return $this;
	}

	public function getExcludeDateStart()
	{
		return $this->excludeDateStart;
	}

	public function setExcludeDateEnd($dateEnd)
	{
		$this->excludeDateEnd = $dateEnd;

		return $this;
	}

	public function getExcludeDateEnd()
	{
		return $this->excludeDateEnd;
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

	public function addExcludeValue($value)
	{
		$this->excludeValues[] = $value;

		return $this;
	}

	public function getExcludeValues()
	{
		return $this->excludeValues;
	}

	public function setExcludeThreat($threat)
	{
		$this->excludeThreat = $threat;

		return $this;
	}

	public function getExcludeThreat()
	{
		return $this->excludeThreat;
	}

	public function setExcludeNoWhitelistRule($noRule)
	{
		$this->excludeNoWhitelistRule = $noRule;

		return $this;
	}

	public function getExcludeNoWhitelistRule()
	{
		return $this->excludeNoWhitelistRule;
	}

	public function setExcludeBrokenWhitelistRule($brokenRule)
	{
		$this->excludeBrokenWhitelistRule = $brokenRule;

		return $this;
	}

	public function getExcludeBrokenWhitelistRule()
	{
		return $this->excludeBrokenWhitelistRule;
	}

	public function setExcludeCriticalImpact($criticalImpact)
	{
		$this->excludeCriticalImpact = $criticalImpact;

		return $this;
	}

	public function getExcludeCriticalImpact()
	{
		return $this->excludeCriticalImpact;
	}
}
