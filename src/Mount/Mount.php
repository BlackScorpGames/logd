<?php
namespace  BlackScorpGames\logd\Mount;

use BlackScorpGames\logd\Mount\MountEntity;


class Mount implements MountEntity
{
    private $ID            = 0;
    
    private $name          = '';
    
    private $desc          = '';
    
    private $category      = '';
    
    private $buff          = '';
    
    private $costGems      = 0;
    
    private $costGold      = 0;
    
    private $active        = true;
    
    private $forestFights  = 0;
    
    private $newDay        = '';
    
    private $recharge      = '';
    
    private $partRecharge  = '';
    
    private $feedCost      = 20;
    
    private $location      = 'all';
    
    private $dkCost        = 0;
    
    public function __construct() {
        $this->ID = 0;
    }
    
    public function getID() : int 
    {
        return $this->ID;
    }

    public function getName() : string
    {
        return (string)$this->name;
    }

    public function getDesc() : string 
    {
        return $this->desc;
    }

    public function getCategory() : string 
    {
        return $this->category;
    }

    public function getBuff() : string 
    {
        return $this->buff;
    }

    public function getCostGems() : int 
    {
        return $this->costGems;
    }

    public function getCostGold() : int 
    {
        return $this->costGold;
    }

    public function getActive() : bool 
    {
        return $this->active;
    }

    public function getForestFights() : int 
    {
        return $this->forestFights;
    }

    public function getNewDay() : string 
    {
        return $this->newDay;
    }

    public function getRecharge() : string 
    {
        return $this->recharge;
    }

    public function getPartRecharge() : string 
    {
        return $this->partRecharge;
    }

    public function getFeedCost() : int 
    {
        return $this->feedCost;
    }

    public function getLocation() : string 
    {
        return $this->location;
    }

    public function getDkCost() : int 
    {
        return $this->dkCost;
    }

    public function setID(int $ID) : void 
    {
        $this->ID = $ID;
    }

    public function setName(string $name) : void 
    {
        $this->name = $name;
    }

    public function setDesc(string $desc) : void 
    {
        $this->desc = $desc;
    }

    public function setCategory(string $category) : void 
    {
        $this->category = $category;
    }

    public function setBuff(string $buff) : void 
    {
        $this->buff = $buff;
    }

    public function setCostGems(int $costGems) : void 
    {
        $this->costGems = $costGems;
    }

    public function setCostGold(int $costGold) : void 
    {
        $this->costGold = $costGold;
    }

    public function setActive(bool $active) : void 
    {
        $this->active = $active;
    }

    public function setForestFights(int $forestFights) : void 
    {
        $this->forestFights = $forestFights;
    }

    public function setNewDay(string $newDay) : void 
    {
        $this->newDay = $newDay;
    }

    public function setRecharge(string $recharge) : void 
    {
        $this->recharge = $recharge;
    }

    public function setPartRecharge(string $partRecharge) : void 
    {
        $this->partRecharge = $partRecharge;
    }

    public function setFeedCost(int $feedCost) : void 
    {
        $this->feedCost = $feedCost;
    }

    public function setLocation(string $location) : void 
    {
        $this->location = $location;
    }

    public function setDkCost(int $dkCost) : void 
    {
        $this->dkCost = $dkCost;
    }
    
    public function setAllOutOfStdClass($mount) : void
    {
        $this->ID = $mount->mountid ?? 0;
        $this->name = $mount->mountname;
        $this->desc = $mount->mountdesc;
        $this->category = $mount->mountcategory;
        $this->buff = $mount->mountbuff;
        $this->costGems = $mount->mountcostgems;
        $this->costGold = $mount->mountcostgold;
        $this->active = (bool)$mount->mountactive;
        $this->forestFights = $mount->mountforestfights;
        $this->newDay = $mount->newday;
        $this->recharge = $mount->recharge;
        $this->partRecharge = $mount->partrecharge;
        $this->feedCost = $mount->mountfeedcost;
        $this->location = $mount->mountlocation;
        $this->dkCost = $mount->mountdkcost;
    }
}
