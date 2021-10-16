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

use Doctrine\ORM\Mapping as ORM;

/**
 * BlacklistFilter
 *
 * @ORM\Table(name="blacklist_filters")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\BlacklistFilterRepository")
 */
class BlacklistFilter
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
     * @ORM\ManyToMany(targetEntity="Parameter")
     * @ORM\JoinTable(name="blacklist_parameters",
     *      joinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")}
     * )
     **/
    private $parameters;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="tags_filters",
     *      joinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     **/
    private $tags;


    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function addTag(\Swd\AnalyzerBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    public function removeTag(\Swd\AnalyzerBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    public function getTags()
    {
        return $this->tags;
    }
}
