<?php


namespace Logd\Core\App\Repository;

use Logd\Core\Entity\User as UserEntity;
use Logd\Core\Repository\User as UserRepository;
use PDO;

/**
 * Class PDOUser
 *
 * @package Logd\Core\App\Repository
 */
class PDOUser implements UserRepository{

    /**
     * @var UserEntity[]
     */
    private $users = array();
    /**
     * @var PDO
     */
    private $connection = null;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection){
        $this->connection = $connection;
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

    /**
     * @param int $userId
     * @param string $username
     * @param string $passwordHash
     * @return UserEntity
     */
    public function create($userId, $username, $passwordHash)
    {
        $user =  new UserEntity($userId,$username, $passwordHash);
        return $user;
    }

    public function add(UserEntity $user)
    {
      $this->users[$user->getUserId()] = $user;
    }

} 