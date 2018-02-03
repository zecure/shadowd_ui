<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2018 Hendrik Buchwald <hb@zecure.org>
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
 * BlacklistRule
 *
 * @ORM\Table(name="blacklist_rules")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\BlacklistRuleRepository")
 */
class BlacklistRule
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
     * @var Profile
     *
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="blacklistRules")
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
     * @ORM\Column(name="threshold", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $threshold;

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

    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;

        return $this;
    }

    public function getThreshold()
    {
        return $this->threshold;
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
