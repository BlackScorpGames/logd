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
    
    public function getMount(int $mountID=0) : MountEntity 
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
                . $this->dbPrefix . 'mounts'
            . ' WHERE '
                . 'mountid=:mountID';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':mountID', $mountID);
        $stmt->execute();
        $row = $stmt->fetch();
        $response = new Mount();
        $response->setAllOutOfStdClass($row);
        return $response;
    }
    
    public function getName(MountEntity $mount)
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
