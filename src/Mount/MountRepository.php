<?php

namespace blackscorp\logd\Mount;

use PDO;


use blackscorp\logd\Mount\{MountEntity, MountRepositoryInteface};
use translator;

class MountRepository implements MountRepositoryInteface
{

    private $pdo        =   null;
    
    private $dbPrefix   =   '';
    
    public function __construct(\PDO $pdo, string $dbPrefix = '') 
    {
        $this->pdo = $pdo;
        $this->dbPrefix = $dbPrefix;
    }
    
    public function create(MountEntity $mount) 
    {
        $sql = ('INSERT INTO '
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
                    . ')');
        $bindParam = [
                        ':name'         => $mount->getName(),
                        ':desc'         => $mount->getDesc(),
                        ':category'     => $mount->getCategory(),
                        ':buff'         => $mount->getBuff(),
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
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindParam);
    }
    
    public function update(MountEntity $mount) 
    {
        $sql = ('UPDATE '
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
                    . '`mountid`=:mountID');        
        $bindParam = [
                        ':name'         => $mount->getName(),
                        ':desc'         => $mount->getDesc(),
                        ':category'     => $mount->getCategory(),
                        ':buff'         => $mount->getBuff(),
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
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindParam);
    }
    
    public function delete(int $id)
    {
        $sql = ('DELETE FROM '
                    .'`'. $this->dbPrefix . 'mounts` '
              . 'WHERE '
                    . '`mountid`=:mountID');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':mountID' => $id]);
    }

    public function findMount(int $mountID=0) : MountEntity 
    {
        $sql = 'SELECT '
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
                . '`'. $this->dbPrefix . 'mounts` '
            . ' WHERE '
                . '`mountid`=:mountID';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':mountID', $mountID);
        $stmt->execute();
        $row = $stmt->fetch();
        $response = new Mount();
        $response->setAllOutOfStdClass($row);
        return $response;
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
