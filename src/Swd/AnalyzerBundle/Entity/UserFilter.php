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
 * UserFilter
 */
class UserFilter
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var \ArrayCollection
	 */
	private $includeUserIds;

	/**
	 * @var \ArrayCollection
	 */
	private $includeUsernames;

	/**
	 * @var \ArrayCollection
	 */
	private $includeEmails;

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
	private $excludeUserIds;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeUsernames;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeEmails;

	/**
	 * @var \DateTime
	 */
	private $excludeDateStart;

	/**
	 * @var \DateTime
	 */
	private $excludeDateEnd;


	public function __construct()
	{
		$this->includeUserIds = new ArrayCollection();
		$this->includeUsernames = new ArrayCollection();
		$this->includeEmails = new ArrayCollection();
		$this->excludeUserIds = new ArrayCollection();
		$this->excludeUsernames = new ArrayCollection();
		$this->excludeEmails = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function addIncludeUserId($userId)
	{
		$this->includeUserIds[] = $userId;

		return $this;
	}

	public function getIncludeUserIds()
	{
		return $this->includeUserIds;
	}

	public function addIncludeUsername($username)
	{
		$this->includeUsernames[] = $username;

		return $this;
	}

	public function getIncludeUsernames()
	{
		return $this->includeUsernames;
	}

	public function addIncludeEmail($email)
	{
		$this->includeEmails[] = $email;

		return $this;
	}

	public function getIncludeEmails()
	{
		return $this->includeEmails;
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

	public function addExcludeUserId($userId)
	{
		$this->excludeUserIds[] = $userId;

		return $this;
	}

	public function getExcludeUserIds()
	{
		return $this->excludeUserIds;
	}

	public function addExcludeUsername($username)
	{
		$this->excludeUsernames[] = $username;

		return $this;
	}

	public function getExcludeUsernames()
	{
		return $this->excludeUsernames;
	}

	public function addExcludeEmail($email)
	{
		$this->excludeEmails[] = $email;

		return $this;
	}

	public function getExcludeEmails()
	{
		return $this->excludeEmails;
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
}
