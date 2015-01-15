<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2015 Hendrik Buchwald <hb@zecure.org>
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
 * Tag
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\TagRepository")
 */
class Tag
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
	 * @ORM\Column(name="tag", type="text")
	 */
	private $tag;

	/**
	 * @ORM\ManyToMany(targetEntity="BlacklistFilter")
	 * @ORM\JoinTable(name="tags_filters",
	 *	  joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
	 *	  inverseJoinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")}
	 * )
	 **/
	private $filters;


	public function __construct()
	{
		$this->filters = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setTag($tag)
	{
		$this->tag = $tag;

		return $this;
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function addFilter(\Swd\AnalyzerBundle\Entity\BlacklistFilter $filters)
	{
		$this->filters[] = $filters;

		return $this;
	}

	public function removeFilter(\Swd\AnalyzerBundle\Entity\BlacklistFilter $filters)
	{
		$this->filters->removeElement($filters);
	}

	public function getFilters()
	{
		return $this->filters;
	}
}
