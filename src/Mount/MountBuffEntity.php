<?php

namespace blackscorp\logd\Mount;

interface MountBuffEntity 
{
    public function getMountBuffAsArray() : array;
        
    public function setMountBuffOutOfArray(array $mountBuffArray);
        
    public function getMountBuffSerialized() : string;
    
    public function setMountBuffUnserialized(string $serializedMountBuff);
       
    public function getName() : string;
    
    public function getRoundMsg() : string;
    
    public function getWearoff() : string;
    
    public function getEffectMsg() : string;
    
    public function getEffectNoDmgMsg() : string;
    
    public function getEffectFailMsg() : string;
    
    public function getRounds() : int;
    
    public function getAtkMod() : string;
    
    public function getDefMod() :string;
    
    public function getInvulnerable() : string;
    
    public function getRegen() : string;
    
    public function getMinionCount() : string;
    
    public function getMinBadGuyDamage() : string;
    
    public function getMaxBadGuyDamage() : string;
    
    public function getMinGoodGuyDamage() : string;
    
    public function getMaxGoodGuyDamage() : string;
    
    public function getLifeTap() : string;
    
    public function getDamageShield() : string;
    
    public function getBadGuyDmgMod() : string;
    
    public function getBadGuyAtkMod() : string;
    
    public function getBadGuyDefMod() : string;
    
    public function setName(string $name) : void;
    
    public function setRoundMsg(string $roundMsg) : void;
    
    public function setWearoff(string $wearoff) : void;
    
    public function setEffectMsg(string $effectMsg) : void;
    
    public function setEffectNoDmgMsg(string $effectNoDmgMsg) : void;
    
    public function setEffectFailMsg(string $effectFailMsg) : void;
    
    public function setRounds(int $rounds) : void;
    
    public function setAtkMod(string $atkMod) : void;
    
    public function setDefMod(string $defMod) : void;
    
    public function setInvulnerable(string $invulnerable) : void;
    
    public function setRegen(string $regen) : void;
    
    public function setMinionCount(string $minionCount) : void;
    
    public function setMinBadGuyDamage(string $minBadGuyDamage) : void;
    
    public function setMaxBadGuyDamage(string $maxBadGuyDamage) : void;
    
    public function setMinGoodGuyDamage(string $minGoodGuyDamage) : void;
    
    public function setMaxGoodGuyDamage(string $maxGoodGuyDamage) : void;
    
    public function setLifeTap(string $lifeTap) : void;
    
    public function setDamageShield(string $damageShield) : void;
    
    public function setBadGuyDmgMod(string $badGuyDmgMod) : void;
    
    public function setBadGuyAtkMod(string $badGuyAtkMod) : void;
    
    public function setBadGuyDefMod(string $badGuyDefMod) : void;
}
