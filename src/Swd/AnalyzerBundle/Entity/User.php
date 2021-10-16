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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Swd\AnalyzerBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Swd\AnalyzerBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(groups={"add"})
     * @Assert\Length(min=5, groups={"add"})
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     * @Assert\NotBlank()
     */
    private $role;

    /**
     * @ORM\Column(name="change_password", type="boolean")
     */
    private $changePassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     **/
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="Setting", mappedBy="user")
     */
    private $setting;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password, $hash = true)
    {
        if ($password && $hash) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getRoles()
    {
        if ($this->role == 1) {
            return array('ROLE_ADMIN');
        } else {
            return array('ROLE_USER');
        }
    }

    public function setChangePassword($changePassword)
    {
        $this->changePassword = $changePassword;

        return $this;
    }

    public function getChangePassword()
    {
        return $this->changePassword;
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

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    public function setSetting(\Swd\AnalyzerBundle\Entity\Setting $setting)
    {
        $this->setting = $setting;

        return $this;
    }

    public function getSetting()
    {
        return $this->setting;
    }
}
