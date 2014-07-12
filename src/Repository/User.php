<?php


namespace Logd\Core\Repository;
use Logd\Core\Entity\User as UserEntity;

/**
 * Interface User
 *
 * @package Logd\Core\Repository
 */
interface User {
    /**
     * @param int $userId
     * @param string $username
     * @param string $passwordHash
     * @return UserEntity
     */
    public function create($userId,$username,$passwordHash);

    /**
     * @return int
     */
    public function getUniqueId();
    /**
     * @param UserEntity $user
     * @return void
     */
    public function add(UserEntity $user);
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

    /**
     * @param string $username
     * @return UserEntity|null
     */
    public function findByUsername($username);
} 