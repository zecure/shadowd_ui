<?php

/*
 * Shadow Daemon -- High-Interaction Web Honeypot
 *
 *   Copyright (C) 2014 Hendrik Buchwald <hb@zecure.org>
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
	 * @var integer
	 */
	private $parameterId;

	/**
	 * @var integer
	 */
	private $profileId;

	/**
	 * @var integer
	 */
	private $requestId;

	/**
	 * @var smallint
	 */
	private $learning;

	/**
	 * @var \ArrayCollection
	 */
	private $searchCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $searchClientIPs;

	/**
	 * @var \DateTime
	 */
	private $dateStart;

	/**
	 * @var \DateTime
	 */
	private $dateEnd;

	/**
	 * @var \ArrayCollection
	 */
	private $searchPaths;

	/**
	 * @var \ArrayCollection
	 */
	private $searchValues;

	/**
	 * @var boolean
	 */
	private $threat;

	/**
	 * @var boolean
	 */
	private $noRule;

	/**
	 * @var boolean
	 */
	private $brokenRule;

	/**
	 * @var boolean
	 */
	private $criticalImpact;

	/**
	 * @var \ArrayCollection
	 */
	private $ignoreCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $ignoreClientIPs;

	/**
	 * @var \ArrayCollection
	 */
	private $ignorePaths;


	public function __construct()
	{
		$this->searchCallers = new ArrayCollection();
		$this->searchClientIPs = new ArrayCollection();
		$this->searchPaths = new ArrayCollection();
		$this->searchValues = new ArrayCollection();
		$this->ignoreCallers = new ArrayCollection();
		$this->ignoreClientIPs = new ArrayCollection();
		$this->ignorePaths = new ArrayCollection();
		$this->learning = 0;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setParameterId($parameterId)
	{
		$this->parameterId = $parameterId;

		return $this;
	}

	public function getParameterId()
	{
		return $this->parameterId;
	}

	public function setProfileId($profileId)
	{
		$this->profileId = $profileId;

		return $this;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function setRequestId($requestId)
	{
		$this->requestId = $requestId;

		return $this;
	}

	public function getProfileId()
	{
		return $this->profileId;
	}

	public function setLearning($learning)
	{
		$this->learning = $learning;

		return $this;
	}

	public function getLearning()
	{
		return $this->learning;
	}

	public function setDateStart($dateStart)
	{
		$this->dateStart = $dateStart;

		return $this;
	}

	public function getDateStart()
	{
		return $this->dateStart;
	}

	public function setDateEnd($dateEnd)
	{
		$this->dateEnd = $dateEnd;

		return $this;
	}

	public function getDateEnd()
	{
		return $this->dateEnd;
	}

	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setThreat($threat)
	{
		$this->threat = $threat;

		return $this;
	}

	public function getThreat()
	{
		return $this->threat;
	}

	public function setNoRule($noRule)
	{
		$this->noRule = $noRule;

		return $this;
	}

	public function getNoRule()
	{
		return $this->noRule;
	}

	public function setBrokenRule($brokenRule)
	{
		$this->brokenRule = $brokenRule;

		return $this;
	}

	public function getBrokenRule()
	{
		return $this->brokenRule;
	}

	public function setCriticalImpact($criticalImpact)
	{
		$this->criticalImpact = $criticalImpact;

		return $this;
	}

	public function getCriticalImpact()
	{
		return $this->criticalImpact;
	}

	public function addSearchCaller($caller)
	{
		$this->searchCallers[] = $caller;

		return $this;
	}

	public function getSearchCallers()
	{
		return $this->searchCallers;
	}

	public function addSearchClientIP($clientIP)
	{
		$this->searchClientIPs[] = $clientIP;

		return $this;
	}

	public function getSearchClientIPs()
	{
		return $this->searchClientIPs;
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

	public function addSearchValue($value)
	{
		$this->searchValues[] = $value;

		return $this;
	}

	public function getSearchValues()
	{
		return $this->searchValues;
	}

	public function addIgnoreCaller($caller)
	{
		$this->ignoreCallers[] = $caller;

		return $this;
	}

	public function getIgnoreCallers()
	{
		return $this->ignoreCallers;
	}

	public function addIgnoreClientIP($clientIP)
	{
		$this->ignoreClientIPs[] = $clientIP;

		return $this;
	}

	public function getIgnoreClientIPs()
	{
		return $this->ignoreClientIPs;
	}

	public function addIgnorePath($path)
	{
		$this->ignorePaths[] = $path;

		return $this;
	}

	public function getIgnorePaths()
	{
		return $this->ignorePaths;
	}
}
