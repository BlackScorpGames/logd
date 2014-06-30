<?php

namespace Logd\Core\Request;


/**
 * Class CreateAccount
 *
 * @package Logd\Core\Request
 */
class CreateAccount {
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $passwordConfirm;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $gender;

    /**
     * @param $username
     * @param $password
     * @param $passwordConfirm
     * @param $gender
     */
    public function __construct($username,$password,$passwordConfirm,$gender){
        $this->username = $username;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
        $this->gender = $gender;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

}