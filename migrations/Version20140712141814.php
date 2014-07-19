<?php

namespace Logd\Core\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140712141814 extends AbstractMigration
{
    /**
     * @var Schema $schema
     */
    private $schema;
    /**
     * @param $name
     * @return Table
     */
    private function createTable($name){

        return $this->schema->createTable($this->getTableName($name));
    }
    private function getTableName($name){
        $params = $this->connection->getParams();
        $tablePrefix = $params['tablePrefix'];
        return $tablePrefix.$name;
    }
    private function dropTable($name){
        $this->schema->dropTable($this->getTableName($name));
    }
    public function up(Schema $schema)
    {
        $this->schema = $schema;


        $table = $this->createTable('users');
        $table->addColumn('userId', Type::INTEGER, array('length' => 11, 'autoincrement' => true));
        $table->addColumn('username', Type::STRING, array('length' => 254));
        $table->addColumn('password', Type::STRING, array('length' => 254));
        $table->addColumn('email', Type::STRING);
        $table->addColumn('lastLogin', Type::DATETIME, array('notnull' => false));
        $table->addColumn('registered', Type::DATETIME, array('notnull' => false));
        $table->addColumn('lastAction', Type::DATETIME, array('notnull' => false));
        $table->setPrimaryKey(array("userId"));

    }

    public function down(Schema $schema)
    {
        $this->dropTable('users');
    }
}
