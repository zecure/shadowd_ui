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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Request
 *
 * @ORM\Table(name="requests")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\RequestRepository")
 */
class Request
{
	/**
	 * @ORM\ManyToOne(targetEntity="Profile", inversedBy="requests")
	 * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
	 */
	protected $profile;

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
	 * @ORM\Column(name="caller", type="text")
	 */
	private $caller;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="learning", type="smallint")
	 *
	 * @Assert\Range(
	 *	  min = 0,
	 *	  max = 1
	 * )
	 */
	private $learning;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="client_ip", type="text")
	 */
	private $clientIP;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date", type="datetime")
	 */
	private $date;

	/**
	 * @ORM\OneToMany(targetEntity="Parameter", mappedBy="request")
	 */
	protected $parameters;


	public function __construct()
	{
		$this->parameters = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setCaller($caller)
	{
		$this->caller = $caller;

		return $this;
	}

	public function getCaller()
	{
		return $this->caller;
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

	public function setClientIP($clientIP)
	{
		$this->clientIP = $clientIP;

		return $this;
	}

	public function getClientIP()
	{
		return $this->clientIP;
	}

	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function addParameter(\Swd\AnalyzerBundle\Entity\Parameter $parameters)
	{
		$this->parameters[] = $parameters;

		return $this;
	}

	public function removeParameter(\Swd\AnalyzerBundle\Entity\Parameter $parameters)
	{
		$this->parameters->removeElement($parameters);
	}

	public function getParameters()
	{
		return $this->parameters;
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
}
