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
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profile
 *
 * @ORM\Table(name="profiles")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\ProfileRepository")
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
	 * @ORM\Column(name="server_ip", type="text")
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
	 * @ORM\Column(name="learning_enabled", type="smallint")
	 *
	 * @Assert\Range(
	 *	  min = 0,
	 *	  max = 1
	 * )
	 * @Assert\NotBlank()
	 */
	private $learningEnabled;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="whitelist_enabled", type="smallint")
	 *
	 * @Assert\Range(
	 *	  min = 0,
	 *	  max = 1
	 * )
	 * @Assert\NotBlank()
	 */
	private $whitelistEnabled;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="blacklist_enabled", type="smallint")
	 *
	 * @Assert\Range(
	 *	  min = 0,
	 *	  max = 1
	 * )
	 * @Assert\NotBlank()
	 */
	private $blacklistEnabled;

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
	 * @ORM\OneToMany(targetEntity="BlacklistRule", mappedBy="profile")
	 */
	protected $blacklistRules;

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
		$this->threshold = 20;
		$this->requests = new ArrayCollection();
		$this->blacklistRules = new ArrayCollection();
		$this->whitelistRules = new ArrayCollection();
		$this->date = new \DateTime();
		$this->whitelistEnabled = 1;
		$this->blacklistEnabled = 1;
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

	public function setLearningEnabled($learningEnabled)
	{
		$this->learningEnabled = $learningEnabled;

		return $this;
	}

	public function getLearningEnabled()
	{
		return $this->learningEnabled;
	}

	public function setWhitelistEnabled($whitelistEnabled)
	{
		$this->whitelistEnabled = $whitelistEnabled;

		return $this;
	}

	public function getWhitelistEnabled()
	{
		return $this->whitelistEnabled;
	}

	public function setBlacklistEnabled($blacklistEnabled)
	{
		$this->blacklistEnabled = $blacklistEnabled;

		return $this;
	}

	public function getBlacklistEnabled()
	{
		return $this->blacklistEnabled;
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

	public function addBlacklistRules(\Swd\AnalyzerBundle\Entity\BlacklistRule $blacklistRule)
	{
		$this->blacklistRules[] = $blacklistRule;

		return $this;
	}

	public function removeBlacklistRule(\Swd\AnalyzerBundle\Entity\BlacklistRule $blacklistRule)
	{
		$this->blacklistRules->removeElement($blacklistRule);
	}

	public function getBlacklistRules()
	{
		return $this->blacklistRules;
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
