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
 * IntegrityRule
 *
 * @ORM\Table(name="integrity_rules")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\IntegrityRuleRepository")
 */
class IntegrityRule
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
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="integrityRules")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * @ORM\OrderBy({"id" = "ASC"})
     *
     * @Assert\NotBlank()
     */
    protected $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="caller", type="text")
     *
     * @Assert\NotBlank()
     */
    private $caller;

    /**
     * @var string
     *
     * @ORM\Column(name="algorithm", type="text")
     *
     * @Assert\NotBlank()
     */
    private $algorithm;

    /**
     * @var string
     *
     * @ORM\Column(name="digest", type="text")
     *
     * @Assert\NotBlank()
     */
    private $digest;

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
     * @var boolean
     */
    private $conflict;


    public function __construct()
    {
        $this->date = new \DateTime();
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

    public function setProfile(\Swd\AnalyzerBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setConflict($conflict)
    {
        $this->conflict = $conflict;

        return $this;
    }

    public function hasConflict()
    {
        return $this->conflict;
    }
}
