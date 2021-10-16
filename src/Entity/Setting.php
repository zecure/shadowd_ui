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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Swd\AnalyzerBundle\Entity\Setting
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="page_limit", type="integer")
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 1000
     * )
     * @Assert\NotBlank()
     */
    private $pageLimit;

    /**
     * @ORM\Column(name="sort_order", type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $sortOrder;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Regex("/^\w+$/")
     */
    private $theme;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Regex("/^\w+$/")
     * @Assert\Length(
     *      min = 2,
     *      max = 10
     * )
     */
    private $locale;

    /**
     * @ORM\Column(name="open_filter", type="boolean")
     */
    private $openFilter;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="setting")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @Assert\NotBlank(groups={"change_password"})
     */
    private $oldPassword;

    /**
     * @Assert\NotBlank(groups={"change_password"})
     * @Assert\Length(min=5, groups={"change_password"})
     */
    private $newPassword;

    public function __construct()
    {
        $this->pageLimit = 50;
        $this->sortOrder = 0;
        $this->theme = 'united';
        $this->locale = 'en';
        $this->openFilter = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPageLimit($pageLimit)
    {
        $this->pageLimit = $pageLimit;

        return $this;
    }

    public function getPageLimit()
    {
        return $this->pageLimit;
    }

    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function getSortOrderText()
    {
        if ($this->sortOrder == 1) {
            return 'asc';
        } else {
            return 'desc';
        }
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function isLocaleRTL()
    {
        if (!$this->locale) {
            return false;
        }

        $rtl = ['he'];
        return in_array($this->locale, $rtl);
    }

    public function setOpenFilter($openFilter)
    {
        $this->openFilter = $openFilter;

        return $this;
    }

    public function getOpenFilter()
    {
        return $this->openFilter;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
