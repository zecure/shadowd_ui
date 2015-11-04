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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * IntegrityHash
 *
 * @ORM\Table(name="integrity_hashes")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\IntegrityHashRepository")
 */
class IntegrityHash
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="algorithm", type="text")
	 */
	private $algorithm;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="digest", type="text")
	 */
	private $digest;

	/**
	 * @ORM\ManyToOne(targetEntity="Request", inversedBy="hashes")
	 * @ORM\JoinColumn(name="request_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $request;


	public function getId()
	{
		return $this->id;
	}

	public function setAlgorithm($algorithm)
	{
		$this->algorithm = $algorithm;

		return $this;
	}

	public function getAlgorithm()
	{
		return $this->algorithm;
	}

	public function setDigest($digest)
	{
		$this->digest = $digest;

		return $this;
	}

	public function getDigest()
	{
		return $this->digest;
	}

	public function setRequest(\Swd\AnalyzerBundle\Entity\Request $request = null)
	{
		$this->request = $request;

		return $this;
	}

	public function getRequest()
	{
		return $this->request;
	}
}
