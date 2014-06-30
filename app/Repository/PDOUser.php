<?php


namespace Logd\Core\App\Repository;

use Logd\Core\Repository\User;
use PDO;
class PDOUser implements User{
    private $connection = null;
    public function __construct(PDO $connection){
        $this->connection = $connection;
    }
} 