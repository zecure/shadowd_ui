<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2017 Hendrik Buchwald <hb@zecure.org>
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
     * @var string
     *
     * @ORM\Column(name="resource", type="text")
     */
    private $resource;

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
     * @var integer
     *
     * @ORM\Column(name="total_integrity_rules", type="integer")
     */
    private $totalIntegrityRules;

    /**
     * @ORM\ManyToMany(targetEntity="IntegrityRule")
     * @ORM\JoinTable(name="integrity_requests",
     *      joinColumns={@ORM\JoinColumn(name="request_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rule_id", referencedColumnName="id")}
     * )
     **/
    private $brokenIntegrityRules;

    /**
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="request")
     */
    protected $parameters;

    /**
     * @ORM\OneToMany(targetEntity="Hash", mappedBy="request")
     */
    protected $hashes;


    public function __construct()
    {
        $this->brokenIntegrityRules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->hashes = new ArrayCollection();
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

    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResource()
    {
        return $this->resource;
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

    public function setTotalIntegrityRules($totalRules)
    {
        $this->totalIntegrityRules = $totalRules;

        return $this;
    }

    public function getTotalIntegrityRules()
    {
        return $this->totalIntegrityRules;
    }

    public function addBrokenIntegrityRule(\Swd\AnalyzerBundle\Entity\IntegrityRule $brokenRules)
    {
        $this->brokenIntegrityRules[] = $brokenRules;

        return $this;
    }

    public function removeBrokenIntegrityRule(\Swd\AnalyzerBundle\Entity\IntegrityRule $brokenRules)
    {
        $this->brokenIntegrityRules->removeElement($brokenRules);
    }

    public function getBrokenIntegrityRules()
    {
        return $this->brokenIntegrityRules;
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

    public function addHash(\Swd\AnalyzerBundle\Entity\Hash $hash)
    {
        $this->hashes[] = $hash;

        return $this;
    }

    public function removeHash(\Swd\AnalyzerBundle\Entity\Hash $hash)
    {
        $this->hashes->removeElement($hash);
    }

    public function getHashes()
    {
        return $this->hashes;
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

    public function getReasons()
    {
        $reasons = array();

        /* Check if no whitelist rule. */
        foreach ($this->getParameters() as $parameter)
        {
            if ($parameter->getTotalWhitelistRules() === 0)
            {
                $reasons[] = 'Unknown';
                break;
            }
        }

        /* Check if broken whitelist rule. */
        foreach ($this->getParameters() as $parameter)
        {
            if ($parameter->getBrokenWhitelistRules()->count() > 0)
            {
                $reasons[] = 'Anomaly';
                break;
            }
        }

        /* Count tags. */
        $tags = array();

        foreach ($this->getParameters() as $parameter)
        {
            /* Do not add tags if impact is not critical. */
            if (!$parameter->getCriticalImpact())
            {
                continue;
            }

            foreach ($parameter->getMatchingBlacklistFilters() as $filter)
            {
                foreach ($filter->getTags() as $tag)
                {
                    if (isset($tags[$tag->getTag()]))
                    {
                        $tags[$tag->getTag()]++;
                    }
                    else
                    {
                        $tags[$tag->getTag()] = 1;
                    }
                }
            }
        }

        /* Add most likely tags. */
        if (!empty($tags))
        {
            array_multisort($tags, SORT_DESC);

            $totalTags = array_sum($tags);
            $counter = 0;

            foreach ($tags as $key => $value)
            {
                /* Limit the amount of tags. */
                if ($counter++ > 2)
                {
                    break;
                }

                /* Filter out unrealistic tags. */
                if (($value / $totalTags) < 0.5)
                {
                    break;
                }

                $reasons[] = $key;
            }
        }

        return $reasons;
    }
}
