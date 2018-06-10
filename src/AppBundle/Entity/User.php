<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * It must be protected as it is inherited from the parent class.
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=100, nullable=true)
     */
    private $twitchId;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $twitchLogin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $streaming;

    public function __construct()
    {
        //To override the __construct() method, be sure to call parent::__construct(), as the base User class depends on this to initialize some fields.
        parent::__construct();
        
    }

    /**
     * Get twitch_id
     *
     * @return string 
     */
    public function getTwitchId()
    {
        return $this->twitchId;
    }

    /**
     * Set twitch_id
     *
     * @param string $twitchId
     * @return User
     */
    public function setTwitchId($twitchId)
    {
        $this->twitchId = $twitchId;
        return $this;
    }

    /**
     * Get twitch_Login
     *
     * @return string 
     */
    public function getTwitchLogin()
    {
        return $this->twitchLogin;
    }

    /**
     * Set twitch_Login
     *
     * @param string $twitchId
     * @return User
     */
    public function setTwitchLogin($twitchLogin)
    {
        $this->twitchLogin = $twitchLogin;
        return $this;
    }

    /**
     * Get streaming
     *
     * @return string 
     */
    public function getStreaming()
    {
        return $this->streaming;
    }

    /**
     * Set streaming
     *
     * @param boolian $streaming
     * @return User
     */
    public function setStreaming($streaming)
    {
        $this->streaming = $streaming;
        return $this;
    }
}