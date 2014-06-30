<?php


namespace Logd\Core\Repository;


/**
 * Interface User
 *
 * @package Logd\Core\Repository
 */
interface User {

    /**
     * @param string $email
     *
     * @return bool
     */
    public function emailExists($email);

    /**
     * @param string $username
     *
     * @return bool
     */
    public function usernameExists($username);
} 