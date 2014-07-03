<?php


namespace Logd\Core\Response;


/**
 * Class CreateAccount
 *
 * @package Logd\Core\Response
 */
class CreateAccount extends Response{
    /**
     * @var string
     */
    public $username ='';
    /**
     * @var string
     */
    public $password ='';
    /**
     * @var string
     */
    public $passwordConfirm ='';
    /**
     * @var string
     */
    public $email='';
    /**
     * @var string
     */
    public $gender='';
} 