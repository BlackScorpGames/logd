<?php
namespace  blackscorp\logd\Mount;

use blackscorp\logd\Mount\{MountEntity, MountBuff, MountBuffEntity};


class Mount implements MountEntity
{
    private $mountid            = 0;
    
    private $mountname          = '';
    
    private $mountdesc          = '';
    
    private $mountcategory      = '';
    
    private $buff               = null;
    
    private $mountcostgems      = 0;
    
    private $mountcostgold      = 0;
    
    private $mountactive        = true;
    
    private $mountforestfights  = 0;
    
    private $newday             = '';
    
    private $recharge           = '';
    
    private $partrecharge       = '';
    
    private $mountfeedcost      = 20;
    
    private $mountlocation      = 'all';
    
    private $mountdkcost        = 0;
    
    public function __construct() 
    {        
        $this->buff = new MountBuff();
        if (isset($this->mountbuff)) {
            $this->buff->setMountBuffUnserialized($this->mountbuff);
            unset($this->mountbuff);
        }
    }
    
    public function getID() : int 
    {
        return $this->mountid;
    }

    public function getName() : string
    {
        return (string)$this->mountname;
    }

    public function getDesc() : string 
    {
        return $this->mountdesc;
    }

    public function getCategory() : string 
    {
        return $this->mountcategory;
    }

    public function getBuff() : ?MountBuffEntity
    {
        return $this->buff;
    }

    public function getCostGems() : int 
    {
        return $this->mountcostgems;
    }

    public function getCostGold() : int 
    {
        return $this->mountcostgold;
    }

    public function getActive() : bool 
    {
        return $this->mountactive;
    }

    public function getForestFights() : int 
    {
        return $this->mountforestfights;
    }

    public function getNewDay() : string 
    {
        return $this->newday;
    }

    public function getRecharge() : string 
    {
        return $this->recharge;
    }

    public function getPartRecharge() : string 
    {
        return $this->partrecharge;
    }

    public function getFeedCost() : int 
    {
        return $this->mountfeedcost;
    }

    public function getLocation() : string 
    {
        return $this->mountlocation;
    }

    public function getDkCost() : int 
    {
        return $this->mountdkcost;
    }

    public function setID(int $ID) : void 
    {
        $this->mountid = $ID;
    }

    public function setName(string $name) : void 
    {
        $this->mountname = $name;
    }

    public function setDesc(string $desc) : void 
    {
        $this->mountdesc = $desc;
    }

    public function setCategory(string $category) : void 
    {
        $this->mountcategory = $category;
    }

    public function setBuff(MountBuffEntity $buff) : void 
    {
        $this->buff = $buff;
    }

    public function setCostGems(int $costGems) : void 
    {
        $this->mountcostgems = $costGems;
    }

    public function setCostGold(int $costGold) : void 
    {
        $this->mountcostgold = $costGold;
    }

    public function setActive(bool $active) : void 
    {
        $this->mountactive = $active;
    }

    public function setForestFights(int $forestFights) : void 
    {
        $this->mountforestfights = $forestFights;
    }

    public function setNewDay(string $newDay) : void 
    {
        $this->newday = $newDay;
    }

    public function setRecharge(string $recharge) : void 
    {
        $this->recharge = $recharge;
    }

    public function setPartRecharge(string $partRecharge) : void 
    {
        $this->partrecharge = $partRecharge;
    }

    public function setFeedCost(int $feedCost) : void 
    {
        $this->mountfeedcost = $feedCost;
    }

    public function setLocation(string $location) : void 
    {
        $this->mountlocation = $location;
    }

    public function setDkCost(int $dkCost) : void 
    {
        $this->mountdkcost = $dkCost;
    }    
}
