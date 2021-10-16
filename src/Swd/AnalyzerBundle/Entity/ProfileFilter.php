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

namespace Swd\AnalyzerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProfileFilter
 */
class ProfileFilter
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ArrayCollection
     */
    private $includeProfileIds;

    /**
     * @var \ArrayCollection
     */
    private $includeServerIPs;

    /**
     * @var \ArrayCollection
     */
    private $includeNames;

    /**
     * @var integer
     */
    private $includeMode;

    /**
     * @var \DateTime
     */
    private $includeDateStart;

    /**
     * @var \DateTime
     */
    private $includeDateEnd;

    /**
     * @var \ArrayCollection
     */
    private $excludeProfileIds;

    /**
     * @var \ArrayCollection
     */
    private $excludeServerIPs;

    /**
     * @var \ArrayCollection
     */
    private $excludeNames;

    /**
     * @var integer
     */
    private $excludeMode;

    /**
     * @var \DateTime
     */
    private $excludeDateStart;

    /**
     * @var \DateTime
     */
    private $excludeDateEnd;


    public function __construct()
    {
        $this->includeProfileIds = new ArrayCollection();
        $this->includeServerIPs = new ArrayCollection();
        $this->includeNames = new ArrayCollection();
        $this->excludeProfileIds = new ArrayCollection();
        $this->excludeServerIPs = new ArrayCollection();
        $this->excludeNames = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addIncludeProfileId($profileId)
    {
        $this->includeProfileIds[] = $profileId;

        return $this;
    }

    public function getIncludeProfileIds()
    {
        return $this->includeProfileIds;
    }

    public function addIncludeServerIP($serverIP)
    {
        $this->includeServerIPs[] = $serverIP;

        return $this;
    }

    public function getIncludeServerIPs()
    {
        return $this->includeServerIPs;
    }

    public function addIncludeName($name)
    {
        $this->includeNames[] = $name;

        return $this;
    }

    public function getIncludeNames()
    {
        return $this->includeNames;
    }

    public function setIncludeMode($mode)
    {
        $this->includeMode = $mode;

        return $this;
    }

    public function getIncludeMode()
    {
        return $this->includeMode;
    }

    public function setIncludeDateStart($dateStart)
    {
        $this->includeDateStart = $dateStart;

        return $this;
    }

    public function getIncludeDateStart()
    {
        return $this->includeDateStart;
    }

    public function setIncludeDateEnd($dateEnd)
    {
        $this->includeDateEnd = $dateEnd;

        return $this;
    }

    public function getIncludeDateEnd()
    {
        return $this->includeDateEnd;
    }

    public function addExcludeProfileId($profileId)
    {
        $this->excludeProfileIds[] = $profileId;

        return $this;
    }

    public function getExcludeProfileIds()
    {
        return $this->excludeProfileIds;
    }

    public function addExcludeServerIP($serverIP)
    {
        $this->excludeServerIPs[] = $serverIP;

        return $this;
    }

    public function getExcludeServerIPs()
    {
        return $this->excludeServerIPs;
    }

    public function addExcludeName($name)
    {
        $this->excludeNames[] = $name;

        return $this;
    }

    public function getExcludeNames()
    {
        return $this->excludeNames;
    }

    public function setExcludeMode($mode)
    {
        $this->excludeMode = $mode;

        return $this;
    }

    public function getExcludeMode()
    {
        return $this->excludeMode;
    }

    public function setExcludeDateStart($dateStart)
    {
        $this->excludeDateStart = $dateStart;

        return $this;
    }

    public function getExcludeDateStart()
    {
        return $this->excludeDateStart;
    }

    public function setExcludeDateEnd($dateEnd)
    {
        $this->excludeDateEnd = $dateEnd;

        return $this;
    }

    public function getExcludeDateEnd()
    {
        return $this->excludeDateEnd;
    }
}
