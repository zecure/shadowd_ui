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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profile
 *
 * @ORM\Table(name="profiles")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\ProfileRepository")
 *
 * @UniqueEntity("serverIP")
 */
class Profile
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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date", type="datetime")
	 **/
	private $date;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="server_ip", type="text", unique=true)
	 *
	 * @Assert\NotBlank()
	 */
	private $serverIP;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="text")
	 *
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="hmac_key", type="text")
	 *
	 * @Assert\NotBlank()
	 */
	private $key;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="learning", type="smallint")
	 *
	 * @Assert\Range(
	 *	  min = 0,
	 *	  max = 1
	 * )
	 * @Assert\NotBlank()
	 */
	private $learning;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="threshold", type="integer")
	 *
	 * @Assert\Range(
	 *	  min = 1,
	 *	  max = 100
	 * )
	 * @Assert\NotBlank()
	 */
	private $threshold;

	/**
	 * @ORM\OneToMany(targetEntity="Request", mappedBy="profile")
	 * @ORM\OrderBy({"date" = "ASC"})
	 */
	protected $requests;

	/**
	 * @ORM\OneToMany(targetEntity="WhitelistRule", mappedBy="profile")
	 */
	protected $whitelistRules;

	/**
	 * @var integer
	 */
	private $learningRequests;

	/**
	 * @var integer
	 */
	private $productiveRequests;


	public function __construct()
	{
		$this->requests = new ArrayCollection();
		$this->whitelistRules = new ArrayCollection();
		$this->date = new \DateTime();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getIdAndName()
	{
		return $this->id . ' (' . $this->name . ')';
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

	public function setServerIP($serverIP)
	{
		$this->serverIP = $serverIP;

		return $this;
	}

	public function getServerIP()
	{
		return $this->serverIP;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setKey($key)
	{
		$this->key = $key;

		return $this;
	}

	public function getKey()
	{
		return $this->key;
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

	public function setThreshold($threshold)
	{
		$this->threshold = $threshold;

		return $this;
	}

	public function getThreshold()
	{
		return $this->threshold;
	}

	public function addRequest(\Swd\AnalyzerBundle\Entity\Request $request)
	{
		$this->requests[] = $request;

		return $this;
	}

	public function removeRequest(\Swd\AnalyzerBundle\Entity\Request $request)
	{
		$this->requests->removeElement($request);
	}

	public function getRequests()
	{
		return $this->requests;
	}

	public function addWhitelistRules(\Swd\AnalyzerBundle\Entity\WhitelistRule $whitelistRule)
	{
		$this->whitelistRules[] = $whitelistRule;

		return $this;
	}

	public function removeWhitelistRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $whitelistRule)
	{
		$this->whitelistRules->removeElement($whitelistRule);
	}

	public function getWhitelistRules()
	{
		return $this->whitelistRules;
	}

	public function setLearningRequests($learningRequests)
	{
		$this->learningRequests = $learningRequests;

		return $this;
	}

	public function getLearningRequests()
	{
		return $this->learningRequests;
	}

	public function setProductiveRequests($productiveRequests)
	{
		$this->productiveRequests = $productiveRequests;

		return $this;
	}

	public function getProductiveRequests()
	{
		return $this->productiveRequests;
	}
}
