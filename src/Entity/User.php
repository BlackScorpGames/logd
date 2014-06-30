<?php
/**
 * Created by PhpStorm.
 * User: vitalij.mik
 * Date: 30.06.14
 * Time: 13:02
 */

namespace Logd\Core\Entity;


/**
 * Class User
 *
 * @package Logd\Core\Entity
 */
class User {
    /**
     * @var int
     */
    private $userId = 0;
    /**
     * @var string
     */
    private $username = '';
    /**
     * @var string
     */
    private $password = '';
    /**
     * @var string
     */
    private $email = '';

    /**
     * @param int $userId
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function __construct( $userId,$username,$password, $email )
    {
        $this->email    = $email;
        $this->userId       = $userId;
        $this->password = $password;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

} 