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
 * ProfileFilter
 */
class ProfileFilter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	private $profileId;

	/**
	 * @var \ArrayCollection
	 */
	private $searchServerIPs;

	/**
	 * @var \ArrayCollection
	 */
	private $searchNames;

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
	private $ignoreServerIPs;

	/**
	 * @var \ArrayCollection
	 */
	private $ignoreNames;


	public function __construct()
	{
		$this->searchServerIPs = new ArrayCollection();
		$this->searchNames = new ArrayCollection();
		$this->ignoreServerIPs = new ArrayCollection();
		$this->ignoreNames = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
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

	public function addSearchServerIP($serverIP)
	{
		$this->searchServerIPs[] = $serverIP;

		return $this;
	}

	public function getSearchServerIPs()
	{
		return $this->searchServerIPs;
	}

	public function addSearchName($name)
	{
		$this->searchNames[] = $name;

		return $this;
	}

	public function getSearchNames()
	{
		return $this->searchNames;
	}

	public function addIgnoreServerIP($serverIP)
	{
		$this->ignoreServerIPs[] = $serverIP;

		return $this;
	}

	public function getIgnoreServerIPs()
	{
		return $this->ignoreServerIPs;
	}

	public function addIgnoreName($name)
	{
		$this->ignoreNames[] = $name;

		return $this;
	}

	public function getIgnoreNames()
	{
		return $this->ignoreNames;
	}
}
