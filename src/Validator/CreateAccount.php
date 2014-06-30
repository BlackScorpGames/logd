<?php
/**
 * Created by PhpStorm.
 * User: vitalij.mik
 * Date: 30.06.14
 * Time: 12:12
 */

namespace Logd\Core\Validator;


abstract class CreateAccount extends Validator{
    public $username = '';
    public $password = '';
    public $passwordConfirm = '';
    public $email = '';
    public $gender = '';
    public $uniqueUsername = false;
    public $uniqueEmail = false;
} 