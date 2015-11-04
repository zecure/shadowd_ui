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

/**
 * Parameter
 *
 * @ORM\Table(name="parameters")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\ParameterRepository")
 */
class Parameter
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
	 * @ORM\Column(name="path", type="text")
	 */
	private $path;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="text")
	 */
	private $value;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="total_whitelist_rules", type="integer")
	 */
	private $totalWhitelistRules;

	/**
	 * @ORM\ManyToMany(targetEntity="WhitelistRule")
	 * @ORM\JoinTable(name="whitelist_parameters",
	 *	  joinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")},
	 *	  inverseJoinColumns={@ORM\JoinColumn(name="rule_id", referencedColumnName="id")}
	 * )
	 **/
	private $brokenWhitelistRules;

	/**
	 * @ORM\ManyToMany(targetEntity="BlacklistFilter")
	 * @ORM\JoinTable(name="blacklist_parameters",
	 *	  joinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")},
	 *	  inverseJoinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")}
	 * )
	 **/
	private $matchingBlacklistFilters;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="critical_impact", type="smallint")
	 */
	private $criticalImpact;

	/**
	 * @ORM\ManyToOne(targetEntity="Request", inversedBy="parameters")
	 * @ORM\JoinColumn(name="request_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $request;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="threat", type="smallint")
	 */
	private $threat;


	public function __construct()
	{
		$this->rules = new \Doctrine\Common\Collections\ArrayCollection();
		$this->brokenWhitelistRules = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setPath($path)
	{
		$this->path = $path;

		return $this;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}

	public function getValue()
	{
		return $this->value;
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

	public function addBrokenWhitelistRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $brokenRules)
	{
		$this->brokenWhitelistRules[] = $brokenRules;

		return $this;
	}

	public function removeBrokenWhitelistRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $brokenRules)
	{
		$this->brokenWhitelistRules->removeElement($brokenRules);
	}

	public function getBrokenWhitelistRules()
	{
		return $this->brokenWhitelistRules;
	}

	public function setTotalWhitelistRules($totalRules)
	{
		$this->totalWhitelistRules = $totalRules;

		return $this;
	}

	public function getTotalWhitelistRules()
	{
		return $this->totalWhitelistRules;
	}

	public function setCriticalImpact($criticalImpact)
	{
		$this->criticalImpact = $criticalImpact;

		return $this;
	}

	public function getCriticalImpact()
	{
		return $this->criticalImpact;
	}

	public function addMatchingBlacklistFilter(\Swd\AnalyzerBundle\Entity\BlacklistFilter $matchingFilters)
	{
		$this->matchingBlacklistFilters[] = $matchingFilters;

		return $this;
	}

	public function removeMatchingBlacklistFilter(\Swd\AnalyzerBundle\Entity\BlacklistFilter $matchingFilters)
	{
		$this->matchingBlacklistFilters->removeElement($matchingFilters);
	}

	public function getMatchingBlacklistFilters()
	{
		return $this->matchingBlacklistFilters;
	}

	public function setThreat($threat)
	{
		$this->threat = $threat;

		return $this;
	}

	public function getThreat()
	{
		return $this->threat;
	}
}
