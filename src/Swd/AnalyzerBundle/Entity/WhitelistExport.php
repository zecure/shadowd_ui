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
 * WhitelistExport
 */
class WhitelistExport
{
	/**
	 * @var entity
	 *
	 * @Assert\NotBlank()
	 */
	private $profile;

	/**
	 * @var text
	 */
	private $base;

	/**
	 * @var \ArrayCollection
	 */
	private $includePaths;

	/**
	 * @var \ArrayCollection
	 */
	private $includeCallers;

	/**
	 * @var \ArrayCollection
	 */
	private $excludePaths;

	/**
	 * @var \ArrayCollection
	 */
	private $excludeCallers;


	public function __construct()
	{
		$this->includePaths = new ArrayCollection();
		$this->includeCallers = new ArrayCollection();
		$this->excludePaths = new ArrayCollection();
		$this->excludeCallers = new ArrayCollection();
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

	public function setBase($base)
	{
		$this->base = $base;

		return $this;
	}

	public function getBase()
	{
		return $this->base;
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

	public function addIncludeCaller($caller)
	{
		$this->includeCallers[] = $caller;

		return $this;
	}

	public function getIncludeCallers()
	{
		return $this->includeCallers;
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

	public function addExcludeCaller($caller)
	{
		$this->excludeCallers[] = $caller;

		return $this;
	}

	public function getExcludeCallers()
	{
		return $this->excludeCallers;
	}
}
