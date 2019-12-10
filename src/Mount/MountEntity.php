<?php
namespace  BlackScorpGames\logd\Mount;

interface MountEntity 
{
    public function getID() : int;

    public function getName() : string;

    public function getDesc() : string;

    public function getCategory() : string;

    public function getBuff() : string;

    public function getCostGems() : int;

    public function getCostGold() : int;

    public function getActive() : bool;

    public function getForestFights() : int;

    public function getNewDay() : string;

    public function getRecharge() : string;

    public function getPartRecharge() : string;

    public function getFeedCost() : int;

    public function getLocation() : string;

    public function getDkCost() : int;

    public function setID(int $ID) : void;

    public function setName(string $name) : void;

    public function setDesc(string $desc): void;

    public function setCategory(string $category): void;

    public function setBuff(string $buff): void;

    public function setCostGems(int $costGems): void;

    public function setCostGold(int $costGold): void;

    public function setActive(bool $active): void;

    public function setForestFights(int $forestFights): void;

    public function setNewDay(string $newDay): void;

    public function setRecharge(string $recharge): void;

    public function setPartRecharge(string $partRecharge): void;

    public function setFeedCost(int $feedCost): void;

    public function setLocation(string $location): void;

    public function setDkCost(int $dkCost): void;
    
    public function setAllOutOfStdClass($mount) : void;
}
