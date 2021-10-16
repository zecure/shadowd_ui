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
 * BlacklistRuleFilter
 */
class BlacklistRuleFilter
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ArrayCollection
     */
    private $includeRuleIds;

    /**
     * @var \ArrayCollection
     */
    private $includeProfileIds;

    /**
     * @var smallint
     */
    private $includeStatus;

    /**
     * @var \ArrayCollection
     */
    private $includeCallers;

    /**
     * @var \ArrayCollection
     */
    private $includePaths;

    /**
     * @var \DateTime
     */
    private $includeDateStart;

    /**
     * @var \DateTime
     */
    private $includeDateEnd;

    /**
     * @var boolean
     */
    private $includeConflict;

    /**
     * @var \ArrayCollection
     */
    private $excludeRuleIds;

    /**
     * @var \ArrayCollection
     */
    private $excludeProfileIds;

    /**
     * @var smallint
     */
    private $excludeStatus;

    /**
     * @var \ArrayCollection
     */
    private $excludeCallers;

    /**
     * @var \ArrayCollection
     */
    private $excludePaths;

    /**
     * @var \DateTime
     */
    private $excludeDateStart;

    /**
     * @var \DateTime
     */
    private $excludeDateEnd;

    /**
     * @var boolean
     */
    private $excludeConflict;


    public function __construct()
    {
        $this->includeRuleIds = new ArrayCollection();
        $this->includeProfileIds = new ArrayCollection();
        $this->includeCallers = new ArrayCollection();
        $this->includePaths = new ArrayCollection();
        $this->excludeRuleIds = new ArrayCollection();
        $this->excludeProfileIds = new ArrayCollection();
        $this->excludeCallers = new ArrayCollection();
        $this->excludePaths = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addIncludeRuleId($ruleId)
    {
        $this->includeRuleIds[] = $ruleId;

        return $this;
    }

    public function getIncludeRuleIds()
    {
        return $this->includeRuleIds;
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

    public function setIncludeStatus($status)
    {
        $this->includeStatus = $status;

        return $this;
    }

    public function getIncludeStatus()
    {
        return $this->includeStatus;
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

    public function addIncludeCaller($caller)
    {
        $this->includeCallers[] = $caller;

        return $this;
    }

    public function getIncludeCallers()
    {
        return $this->includeCallers;
    }

    public function addIncludePath($path)
    {
        $this->includePaths[] = $path;

        return $this;
    }

    public function getIncludePaths()
    {
        return $this->includePaths;
    }

    public function setIncludeConflict($conflict)
    {
        $this->includeConflict = $conflict;

        return $this;
    }

    public function hasIncludeConflict()
    {
        return $this->includeConflict;
    }

    public function addExcludeRuleId($ruleId)
    {
        $this->excludeRuleIds[] = $ruleId;

        return $this;
    }

    public function getExcludeRuleIds()
    {
        return $this->excludeRuleIds;
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

    public function setExcludeStatus($status)
    {
        $this->excludeStatus = $status;

        return $this;
    }

    public function getExcludeStatus()
    {
        return $this->excludeStatus;
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

    public function addExcludeCaller($caller)
    {
        $this->excludeCallers[] = $caller;

        return $this;
    }

    public function getExcludeCallers()
    {
        return $this->excludeCallers;
    }

    public function addExcludePath($path)
    {
        $this->excludePaths[] = $path;

        return $this;
    }

    public function getExcludePaths()
    {
        return $this->excludePaths;
    }

    public function setExcludeConflict($conflict)
    {
        $this->excludeConflict = $conflict;

        return $this;
    }

    public function hasExcludeConflict()
    {
        return $this->excludeConflict;
    }
}
