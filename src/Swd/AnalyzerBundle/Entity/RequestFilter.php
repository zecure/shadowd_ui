<?php

/**
 * Shadow Daemon -- Web Application Firewall
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
 * RequestFilter
 */
class RequestFilter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	private $requestId;

	/**
	 * @var integer
	 */
	private $profileId;

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


	public function __construct()
	{
		$this->searchCallers = new ArrayCollection();
		$this->searchClientIPs = new ArrayCollection();
		$this->ignoreCallers = new ArrayCollection();
		$this->ignoreClientIPs = new ArrayCollection();
		$this->learning = 0;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setRequestId($requestId)
	{
		$this->requestId = $requestId;

		return $this;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function setProfileId($profileId)
	{
		$this->profileId = $profileId;

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
}
