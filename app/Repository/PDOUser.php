<?php


namespace Logd\Core\App\Repository;

use Logd\Core\Entity\User as UserEntity;
use Logd\Core\Repository\User as UserRepository;
use MyProject\Proxies\__CG__\stdClass;
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

    /**
     * @return int
     */
    public function getUniqueId()
    {
      $sql = "SELECT MAX(userId) FROM users";
      $statement = $this->connection->prepare($sql);
      $statement->execute();
      $row = (int)$statement->fetchColumn();
      return ++$row;
    }

    /**
     * @param string $username
     * @return UserEntity|null
     */
    public function findByUsername($username)
    {
        foreach($this->users as $user){
            if($user->getUsername() === $username){
                return $user;
            }
        }
        $sql = $this->getUsersSQL();
        $sql.= ' WHERE username = :username';
        $params = array(
            ':username'=>$username
        );

        $statment = $this->connection->prepare($sql);
        $statment->execute($params);
        $rows = $statment->fetchAll(PDO::FETCH_OBJ);
        foreach($rows as $row){
            $user = $this->rowToEntity($row);
            $this->users[$user->getUserId()] = $user;
        }
        return $user;
    }

    /**
     * Syncronize data with database
     * @return void
     */
    public function sync()
    {

    }

    /**
     * @param stdClass $row
     * @return UserEntity
     */
    private function rowToEntity(stdClass $row){
        $user = $this->create($row->userId,$row->username,$row->password);
        $user->setEmail($row->email);
        return $user;
    }
    private function getUsersSQL(){

       return "SELECT userId,username,password,email,lastLogin,registered,lastAction FROM users";
    }
}