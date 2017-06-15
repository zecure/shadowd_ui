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

/**
 * WhitelistRule
 *
 * @ORM\Table(name="whitelist_rules")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\WhitelistRuleRepository")
 */
class WhitelistRule
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
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="whitelistRules")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * @ORM\OrderBy({"id" = "ASC"})
     *
     * @Assert\NotBlank()
     */
    protected $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text")
     *
     * @Assert\NotBlank()
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="caller", type="text")
     *
     * @Assert\NotBlank()
     */
    private $caller;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_length", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $minLength;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_length", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $maxLength;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     **/
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     *
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="WhitelistFilter", inversedBy="rules")
     * @ORM\JoinColumn(name="filter_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     */
    protected $filter;

    /**
     * @ORM\ManyToMany(targetEntity="Parameter")
     * @ORM\JoinTable(name="whitelist_parameters",
     *      joinColumns={@ORM\JoinColumn(name="rule_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")}
     * )
     **/
    private $parameters;

    /**
     * @var boolean
     */
    private $minLengthConflict;

    /**
     * @var boolean
     */
    private $maxLengthConflict;

    /**
     * @var boolean
     */
    private $filterConflict;


    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
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

    public function setCaller($caller)
    {
        $this->caller = $caller;

        return $this;
    }

    public function getCaller()
    {
        return $this->caller;
    }

    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;

        return $this;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
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

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setFilter(\Swd\AnalyzerBundle\Entity\WhitelistFilter $filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
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

    public function setMinLengthConflict($minLengthConflict)
    {
        $this->minLengthConflict = $minLengthConflict;

        return $this;
    }

    public function hasMinLengthConflict()
    {
        return $this->minLengthConflict;
    }

    public function setMaxLengthConflict($maxLengthConflict)
    {
        $this->maxLengthConflict = $maxLengthConflict;

        return $this;
    }

    public function hasMaxLengthConflict()
    {
        return $this->maxLengthConflict;
    }

    public function setFilterConflict($filterConflict)
    {
        $this->filterConflict = $filterConflict;

        return $this;
    }

    public function hasFilterConflict()
    {
        return $this->filterConflict;
    }
}
