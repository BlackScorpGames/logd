<?php

namespace blackscorp\logd\Mount;

use PDO;
use blackscorp\logd\Mount\{MountEntity, MountRepositoryInteface};
use translator;

class MountRepository
{

    private $pdo        =   null;
    
    private $dbPrefix   =   '';
    
    public function __construct(?\PDO $pdo = null, ?string $dbPrefix = null) 
    {
        $this->pdo = $pdo;
        $this->dbPrefix = $dbPrefix;
    }
    
    private function getSelectQuery() : string
    {
        return 'SELECT '
                . '`mountid`,'
                . '`mountname`,'
                . '`mountdesc`,'
                . '`mountcategory`,'
                . '`mountbuff`,'
                . '`mountcostgems`,'
                . '`mountcostgold`,'
                . '`mountactive`,'
                . '`mountforestfights`,'
                . '`newday`,'
                . '`recharge`,'
                . '`partrecharge`,'
                . '`mountfeedcost`,'
                . '`mountlocation`,'
                . '`mountdkcost`'
            .  'FROM '
                . '`'. $this->dbPrefix . 'mounts` ';
    }
    
    private function getAllBindParam(MountEntity $mount) : array 
    {
        return [
                    ':name'         => $mount->getName(),
                    ':desc'         => $mount->getDesc(),
                    ':category'     => $mount->getCategory(),
                    ':buff'         => $mount->getBuff()->getMountBuffAsArray(),
                    ':costgems'     => $mount->getCostGems(),
                    ':costgold'     => $mount->getCostGold(),
                    ':active'       => (int)$mount->getActive(),
                    ':forestfights' => $mount->getForestFights(),
                    ':newday'       => $mount->getNewDay(),
                    ':recharge'     => $mount->getRecharge(),
                    ':partrecharge' => $mount->getPartRecharge(),
                    ':feedcost'     => $mount->getFeedCost(),
                    ':location'     => $mount->getLocation(),
                    ':dkcost'       => $mount->getDkCost(),
                    ':mountID'      => $mount->getID()
                ];
    }
    
    private function prepareQueryAndExecute(string $query, array $bindParam) : \PDOStatement
    {
        $PDOStatement = $this->pdo->prepare($query);
        $PDOStatement->execute($bindParam);
        return $PDOStatement;
    }
    
    public function create(MountEntity $mount) 
    {
        $query = 'INSERT INTO '
                    .'`'. $this->dbPrefix . 'mounts` '
                    . '('
                        . '`mountname`, '
                        . '`mountdesc`, '
                        . '`mountcategory`, '
                        . '`mountbuff`, '
                        . '`mountcostgems`, '
                        . '`mountcostgold`, '
                        . '`mountactive`, '
                        . '`mountforestfights`, '
                        . '`newday`, '
                        . '`recharge`, '
                        . '`partrecharge`, '
                        . '`mountfeedcost`, '
                        . '`mountlocation`, '
                        . '`mountdkcost`'
                    . ')'
              . 'VALUES '
                    . '('
                        . ':name, '
                        . ':desc, '
                        . ':category, '
                        . ':buff, '
                        . ':costgems, '
                        . ':costgold, '
                        . ':active, '
                        . ':forestfights, '
                        . ':newday, '
                        . ':recharge, '
                        . ':partrecharge, '
                        . ':feedcost, '
                        . ':location, '
                        . ':dkcost'
                    . ')';
        $this->prepareQueryAndExecute($query, $this->getAllBindParam($mount));        
    }
    
    public function update(MountEntity $mount) 
    {
        $query = 'UPDATE '
                    .'`'. $this->dbPrefix . 'mounts` '
              . 'SET '
                    . '`mountname`=:name, '
                    . '`mountdesc`=:desc, '
                    . '`mountcategory`=:category, '
                    . '`mountbuff`=:buff, '
                    . '`mountcostgems`=:costgems, '
                    . '`mountcostgold`=:costgold, '
                    . '`mountactive`=:active, '
                    . '`mountforestfights`=:forestfights, '
                    . '`newday`=:newday, '
                    . '`recharge`=:recharge, '
                    . '`partrecharge`=:partrecharge, '
                    . '`mountfeedcost`=:feedcost, '
                    . '`mountlocation`=:location, '
                    . '`mountdkcost`=:dkcost '
              . 'WHERE '
                    . '`mountid`=:mountID';        
            $this->prepareQueryAndExecute($query, $this->getAllBindParam($mount));
    }
    
    public function delete(int $mountID)
    {
        $query = ('DELETE FROM '
                    .'`'. $this->dbPrefix . 'mounts` '
              . 'WHERE '
                    . '`mountid`=:mountID');

        $this->prepareQueryAndExecute($query, [':mountID' => $mountID]);
    }

    public function findMount(int $mountID=0) : MountEntity 
    {
        $query = $this->getSelectQuery()            
                        . ' WHERE '
                            . '`mountid`=:mountID';
        
        $PDOStatement = $this->prepareQueryAndExecute($query, [':mountID' => $mountID]);
        $mountToBeSearched = $PDOStatement->fetchObject('blackscorp\\logd\\Mount\\Mount');
        
        if (!$mountToBeSearched) {
            $mountToBeSearched = new Mount();
        }
        
        return $mountToBeSearched;
    }
    
    public function findAllMountsSorted()
    {
        $query = $this->getSelectQuery()
                        . 'ORDER BY '
                            . '`mountcategory`, '
                            . '`mountcostgems`, '
                            . '`mountcostgold`';
        
        $PDOStatement = $this->prepareQueryAndExecute($query, []);
        $foundMounts = $PDOStatement->fetchAll();
        
        $mountToBeSearched = [];
        foreach ($foundMounts as $mount)
        {
            $cacheMount = new Mount();
            $cacheMount->setAllOutOfStdClass($mount);
            $mountToBeSearched[] = $cacheMount;
        }
        
        return $mountToBeSearched;
    }


    public function findName(MountEntity $mount)
    {
	translator::tlschema("mountname");
	$name = '';
	$lcname = '';
	if ($mount->getName()!== '') {
		$name = translator::sprintf_translate("Your %s", $mount->getName());
		$lcname = translator::sprintf_translate("your %s", $mount->getName());
	}
	translator::tlschema();
	/** ???
        if (isset($playermount['newname']) && $playermount['newname'] != "") {
		$name = $playermount['newname'];
		$lcname = $playermount['newname'];
	}*/
	return array($name, $lcname);
    }
    
    
}
