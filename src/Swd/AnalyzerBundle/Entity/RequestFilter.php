<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2022 Hendrik Buchwald <hb@zecure.org>
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
 * RequestFilter
 */
class RequestFilter
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ArrayCollection
     */
    private $includeRequestIds;

    /**
     * @var \ArrayCollection
     */
    private $includeProfileIds;

    /**
     * @var \ArrayCollection
     */
    private $includeCallers;

    /**
     * @var \ArrayCollection
     */
    private $includeResources;

    /**
     * @var \ArrayCollection
     */
    private $includeClientIPs;

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
    private $excludeRequestIds;

    /**
     * @var \ArrayCollection
     */
    private $excludeProfileIds;

    /**
     * @var \ArrayCollection
     */
    private $excludeCallers;

    /**
     * @var \ArrayCollection
     */
    private $excludeResources;

    /**
     * @var \ArrayCollection
     */
    private $excludeClientIPs;

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
        $this->includeRequestIds = new ArrayCollection();
        $this->includeProfileIds = new ArrayCollection();
        $this->includeCallers = new ArrayCollection();
        $this->includeResources = new ArrayCollection();
        $this->includeClientIPs = new ArrayCollection();
        $this->excludeRequestIds = new ArrayCollection();
        $this->excludeProfileIds = new ArrayCollection();
        $this->excludeCallers = new ArrayCollection();
        $this->excludeResources = new ArrayCollection();
        $this->excludeClientIPs = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addIncludeRequestId($requestId)
    {
        $this->includeRequestIds[] = $requestId;

        return $this;
    }

    public function getIncludeRequestIds()
    {
        return $this->includeRequestIds;
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

    public function addIncludeCaller($caller)
    {
        $this->includeCallers[] = $caller;

        return $this;
    }

    public function getIncludeCallers()
    {
        return $this->includeCallers;
    }

    public function addIncludeResource($resource)
    {
        $this->includeResources[] = $resource;

        return $this;
    }

    public function getIncludeResources()
    {
        return $this->includeResources;
    }

    public function addIncludeClientIP($clientIP)
    {
        $this->includeClientIPs[] = $clientIP;

        return $this;
    }

    public function getIncludeClientIPs()
    {
        return $this->includeClientIPs;
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

    public function addExcludeRequestId($requestId)
    {
        $this->excludeRequestIds[] = $requestId;

        return $this;
    }

    public function getExcludeRequestIds()
    {
        return $this->excludeRequestIds;
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

    public function addExcludeCaller($caller)
    {
        $this->excludeCallers[] = $caller;

        return $this;
    }

    public function getExcludeCallers()
    {
        return $this->excludeCallers;
    }

    public function addExcludeResource($resource)
    {
        $this->excludeResources[] = $resource;

        return $this;
    }

    public function getExcludeResources()
    {
        return $this->excludeResources;
    }

    public function addExcludeClientIP($clientIP)
    {
        $this->excludeClientIPs[] = $clientIP;

        return $this;
    }

    public function getExcludeClientIPs()
    {
        return $this->excludeClientIPs;
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
