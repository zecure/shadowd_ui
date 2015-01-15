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
 * BlacklistRuleFilter
 */
class BlacklistRuleFilter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	private $ruleId;

	/**
	 * @var integer
	 */
	private $profileId;

	/**
	 * @var smallint
	 */
	private $status;

	/**
	 * @var \ArrayCollection
	 */
	private $searchCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $searchPaths;

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
	private $ignoreCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $ignorePaths;


	public function __construct()
	{
		$this->searchCallers = new ArrayCollection();
		$this->searchPaths = new ArrayCollection();
		$this->ignoreCallers = new ArrayCollection();
		$this->ignorePaths = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setRuleId($ruleId)
	{
		$this->ruleId = $ruleId;

		return $this;
	}

	public function getRuleId()
	{
		return $this->ruleId;
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

	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
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

	public function addSearchPath($path)
	{
		$this->searchPaths[] = $path;

		return $this;
	}

	public function getSearchPaths()
	{
		return $this->searchPaths;
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
