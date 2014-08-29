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
	private $searchUsernames;

	/**
	 * @var \ArrayCollection
	 */
	private $searchEmails;

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
	private $ignoreUsernames;

	/**
	 * @var \ArrayCollection
	 */
	private $ignoreEmails;


	public function __construct()
	{
		$this->searchUsernames = new ArrayCollection();
		$this->ignoreUsernames = new ArrayCollection();
		$this->searchEmails = new ArrayCollection();
		$this->ignoreEmails = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
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

	public function addSearchUsername($username)
	{
		$this->searchUsernames[] = $username;

		return $this;
	}

	public function getSearchUsernames()
	{
		return $this->searchUsernames;
	}

	public function addSearchEmail($email)
	{
		$this->searchEmails[] = $email;

		return $this;
	}

	public function getSearchEmails()
	{
		return $this->searchEmails;
	}

	public function addIgnoreUsername($username)
	{
		$this->ignoreUsernames[] = $username;

		return $this;
	}

	public function getIgnoreUsernames()
	{
		return $this->ignoreUsernames;
	}

	public function addIgnoreEmail($email)
	{
		$this->ignoreEmails[] = $email;

		return $this;
	}

	public function getIgnoreEmails()
	{
		return $this->ignoreEmails;
	}
}
