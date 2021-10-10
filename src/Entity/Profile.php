<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profile
 *
 * @ORM\Table(name="profiles")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
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
     * @Assert\NotBlank(groups={"add"})
     * @Assert\Length(min=5, groups={"add"})
     */
    private $key;

    /**
     * @var integer
     *
     * @ORM\Column(name="mode", type="integer")
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 3
     * )
     */
    private $mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="whitelist_enabled", type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
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
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $blacklistEnabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="integrity_enabled", type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $integrityEnabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="flooding_enabled", type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $floodingEnabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="cache_outdated", type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $cacheOutdated;

    /**
     * @var integer
     *
     * @ORM\Column(name="blacklist_threshold", type="integer")
     *
     * @Assert\Range(
     *      min = 0
     * )
     * @Assert\NotBlank()
     */
    private $blacklistThreshold;

    /**
     * @var integer
     *
     * @ORM\Column(name="flooding_timeframe", type="integer")
     *
     * @Assert\Range(
     *      min = 1
     * )
     * @Assert\NotBlank()
     */
    private $floodingTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="flooding_threshold", type="integer")
     *
     * @Assert\Range(
     *      min = 1
     * )
     * @Assert\NotBlank()
     */
    private $floodingThreshold;

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
     * @ORM\OneToMany(targetEntity="IntegrityRule", mappedBy="profile")
     */
    protected $integrityRules;

    /**
     * @var integer
     */
    private $learningRequests;

    /**
     * @var integer
     */
    private $productiveRequests;

    /**
     * @var Request|null
     */
    private $lastRequest;


    public function __construct()
    {
        $this->serverIP = '*';
        $this->blacklistThreshold = 10;
        $this->floodingTime = 60;
        $this->floodingThreshold = 5;
        $this->requests = new ArrayCollection();
        $this->blacklistRules = new ArrayCollection();
        $this->whitelistRules = new ArrayCollection();
        $this->integrityRules = new ArrayCollection();
        $this->date = new \DateTime();
        $this->mode = 2;
        $this->whitelistEnabled = 0;
        $this->blacklistEnabled = 1;
        $this->integrityEnabled = 0;
        $this->floodingEnabled = 1;
        $this->cacheOutdated = 1;
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

    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    public function getMode()
    {
        return $this->mode;
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

    public function setIntegrityEnabled($integrityEnabled)
    {
        $this->integrityEnabled = $integrityEnabled;

        return $this;
    }

    public function getIntegrityEnabled()
    {
        return $this->integrityEnabled;
    }

    public function setFloodingEnabled($floodingEnabled)
    {
        $this->floodingEnabled = $floodingEnabled;

        return $this;
    }

    public function getFloodingEnabled()
    {
        return $this->floodingEnabled;
    }

    public function setCacheOutdated($cacheOutdated)
    {
        $this->cacheOutdated = $cacheOutdated;

        return $this;
    }

    public function getCacheOutdated()
    {
        return $this->cacheOutdated;
    }

    public function setBlacklistThreshold($blacklistThreshold)
    {
        $this->blacklistThreshold = $blacklistThreshold;

        return $this;
    }

    public function getBlacklistThreshold()
    {
        return $this->blacklistThreshold;
    }

    public function setFloodingTime($floodingTime)
    {
        $this->floodingTime = $floodingTime;

        return $this;
    }

    public function getFloodingTime()
    {
        return $this->floodingTime;
    }

    public function setFloodingThreshold($floodingThreshold)
    {
        $this->floodingThreshold = $floodingThreshold;

        return $this;
    }

    public function getFloodingThreshold()
    {
        return $this->floodingThreshold;
    }

    public function addRequest(Request $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    public function removeRequest(Request $request)
    {
        $this->requests->removeElement($request);
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function addBlacklistRules(BlacklistRule $blacklistRule)
    {
        $this->blacklistRules[] = $blacklistRule;

        return $this;
    }

    public function removeBlacklistRule(BlacklistRule $blacklistRule)
    {
        $this->blacklistRules->removeElement($blacklistRule);
    }

    public function getBlacklistRules()
    {
        return $this->blacklistRules;
    }

    public function addWhitelistRules(WhitelistRule $whitelistRule)
    {
        $this->whitelistRules[] = $whitelistRule;

        return $this;
    }

    public function removeWhitelistRule(WhitelistRule $whitelistRule)
    {
        $this->whitelistRules->removeElement($whitelistRule);
    }

    public function getWhitelistRules()
    {
        return $this->whitelistRules;
    }

    public function addIntegrityRules(IntegrityRule $integrityRule)
    {
        $this->integrityRules[] = $integrityRule;

        return $this;
    }

    public function removeIntegrityRule(IntegrityRule $integrityRule)
    {
        $this->integrityRules->removeElement($integrityRule);
    }

    public function getIntegrityRules()
    {
        return $this->integrityRules;
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

    public function setLastRequest($lastRequest)
    {
        $this->lastRequest = $lastRequest;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }
}
