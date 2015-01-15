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
 * Selector
 */
class Selector
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var text
	 */
	private $subaction;


	public function getId()
	{
		return $this->id;
	}

	public function setSubaction($subaction)
	{
		$this->subaction = $subaction;

		return $this;
	}

	public function getSubaction()
	{
		return $this->subaction;
	}
}
