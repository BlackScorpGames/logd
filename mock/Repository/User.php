<?php
/**
 * Created by PhpStorm.
 * User: vitalij.mik
 * Date: 30.06.14
 * Time: 14:36
 */

namespace Logd\Core\Mock\Repository;
use Logd\Core\Repository\User as UserRepository;

class User implements UserRepository{
    private $users = array();
    public function __construct(array $users){
        $this->users = $users;
    }
    /**
     * @param string $email
     *
     * @return bool
     */
    public function emailExists($email)
    {
        foreach($this->users as $user){
            if($user->getEmail() === $email){
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function usernameExists($username)
    {
        foreach($this->users as $user){
            if($user->getUsername() === $username){
                return true;
            }
        }
        return false;
    }


} 