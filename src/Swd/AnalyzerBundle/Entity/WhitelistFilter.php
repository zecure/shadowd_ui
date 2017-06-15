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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WhitelistFilter
 *
 * @ORM\Table(name="whitelist_filters")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\WhitelistFilterRepository")
 */
class WhitelistFilter
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
     * @ORM\Column(name="rule", type="text")
     */
    private $rule;

    /**
     * @var integer
     *
     * @ORM\Column(name="impact", type="integer")
     */
    private $impact;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="WhitelistRule", mappedBy="filter")
     */
    private $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;

        return $this;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function setImpact($impact)
    {
        $this->impact = $impact;

        return $this;
    }

    public function getImpact()
    {
        return $this->impact;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $rules)
    {
        $this->rules[] = $rules;

        return $this;
    }

    public function removeRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $rules)
    {
        $this->rules->removeElement($rules);
    }

    public function getRules()
    {
        return $this->rules;
    }
}
