<?php


namespace LoGD\Core\App\Repository;

use PDO;
class PDOUser {
    private $connection = null;
    public function __construct(PDO $connection){
        $this->connection = $connection;
    }
} 