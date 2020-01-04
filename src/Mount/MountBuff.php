<?php

namespace blackscorp\logd\Mount;

class MountBuff implements MountBuffEntity
{
    private $name               =   '';
    
    private $roundMsg           =   '';
    
    private $wearoff            =   '';
    
    private $effectMsg          =   '';
    
    private $effectNoDmgMsg     =   '';
    
    private $effectFailMsg      =   '';
    
    private $rounds             =   0;
    
    private $atkMod             =   '';
    
    private $defMod             =   '';
    
    private $invulnerable       = '';
    
    private $regen              =   '';
    
    private $minionCount        =   '';
    
    private $minBadGuyDamage    =   '';
    
    private $maxBadGuyDamage    =   '';
    
    private $minGoodGuyDamage   =   '';
    
    private $maxGoodGuyDamage   =   '';
    
    private $lifeTap            =   '';
    
    private $damageShield       =   '';
    
    private $badGuyDmgMod       =   '';
    
    private $badGuyAtkMod       =   '';
    
    private $badGuyDefMod       =   '';
            
    public function getMountBuffAsArray() : array
    {        
        return get_object_vars($this);
    }
    
    public function setMountBuffOutOfArray(array $mountBuffArray)
    {
        foreach ($mountBuffArray as $property => $value)
        {
            $this->$property = $value;
        }
    }
    
    public function getMountBuffSerialized() : string
    {
        return serialize($this->getMountBuffAsArray());
    }

    public function setMountBuffUnserialized(string $serializedMountBuff)
    {
        $unserializedMountBuff = unserialize($serializedMountBuff);
        if (is_array($unserializedMountBuff))
            $this->setMountBuffOutOfArray(unserialize($serializedMountBuff));
    }
    
    public function getName() : string
    {
        return $this->name;
    }

    public function getRoundMsg() : string
    {
        return $this->roundMsg;
    }

    public function getWearoff() : string 
    {
        return $this->wearoff;
    }

    public function getEffectMsg() : string 
    {
        return $this->effectMsg;
    }

    public function getEffectNoDmgMsg() : string 
    {
        return $this->effectNoDmgMsg;
    }

    public function getEffectFailMsg() : string 
    {
        return $this->effectFailMsg;
    }

    public function getRounds() : int 
    {
        return $this->rounds;
    }

    public function getAtkMod() : string
    {
        return $this->atkMod;
    }

    public function getDefMod() :string 
    {
        return $this->defMod;
    }

    public function getInvulnerable() : string 
    {
        return $this->invulnerable;
    }

    public function getRegen() : string 
    {
        return $this->regen;
    }

    public function getMinionCount() : string 
    {
        return $this->minionCount;
    }

    public function getMinBadGuyDamage() : string 
    {
        return $this->minBadGuyDamage;
    }

    public function getMaxBadGuyDamage() : string 
    {
        return $this->maxBadGuyDamage;
    }

    public function getMinGoodGuyDamage() : string 
    {
        return $this->minGoodGuyDamage;
    }

    public function getMaxGoodGuyDamage() : string 
    {
        return $this->maxGoodGuyDamage;
    }

    public function getLifeTap() : string 
    {
        return $this->lifeTap;
    }

    public function getDamageShield() : string 
    {
        return $this->damageShield;
    }

    public function getBadGuyDmgMod() : string 
    {
        return $this->badGuyDmgMod;
    }

    public function getBadGuyAtkMod() : string 
    {
        return $this->badGuyAtkMod;
    }

    public function getBadGuyDefMod() : string 
    {
        return $this->badGuyDefMod;
    }

    public function setName(string $name) : void 
    {
        $this->name = $name;
    }

    public function setRoundMsg(string $roundMsg) : void 
    {
        $this->roundMsg = $roundMsg;
    }

    public function setWearoff(string $wearoff) : void 
    {
        $this->wearoff = $wearoff;
    }

    public function setEffectMsg(string $effectMsg) : void 
    {
        $this->effectMsg = $effectMsg;
    }

    public function setEffectNoDmgMsg(string $effectNoDmgMsg) : void 
    {
        $this->effectNoDmgMsg = $effectNoDmgMsg;
    }

    public function setEffectFailMsg(string $effectFailMsg) : void 
    {
        $this->effectFailMsg = $effectFailMsg;
    }

    public function setRounds(int $rounds) : void 
    {
        $this->rounds = $rounds;
    }

    public function setAtkMod(string $atkMod) : void 
    {
        $this->atkMod = $atkMod;
    }

    public function setDefMod(string $defMod) : void 
    {
        $this->defMod = $defMod;
    }

    public function setInvulnerable(string $invulnerable) : void 
    {
        $this->invulnerable = $invulnerable;
    }

    public function setRegen(string $regen) : void 
    {
        $this->regen = $regen;
    }

    public function setMinionCount(string $minionCount) : void 
    {
        $this->minionCount = $minionCount;
    }

    public function setMinBadGuyDamage(string $minBadGuyDamage) : void 
    {
        $this->minBadGuyDamage = $minBadGuyDamage;
    }

    public function setMaxBadGuyDamage(string $maxBadGuyDamage) : void 
    {
        $this->maxBadGuyDamage = $maxBadGuyDamage;
    }

    public function setMinGoodGuyDamage(string $minGoodGuyDamage) : void 
    {
        $this->minGoodGuyDamage = $minGoodGuyDamage;
    }

    public function setMaxGoodGuyDamage(string $maxGoodGuyDamage) : void 
    {
        $this->maxGoodGuyDamage = $maxGoodGuyDamage;
    }

    public function setLifeTap(string $lifeTap) : void 
    {
        $this->lifeTap = $lifeTap;
    }

    public function setDamageShield(string $damageShield) : void 
    {
        $this->damageShield = $damageShield;
    }

    public function setBadGuyDmgMod(string $badGuyDmgMod) : void 
    {
        $this->badGuyDmgMod = $badGuyDmgMod;
    }

    public function setBadGuyAtkMod(string $badGuyAtkMod) : void 
    {
        $this->badGuyAtkMod = $badGuyAtkMod;
    }

    public function setBadGuyDefMod(string $badGuyDefMod) : void 
    {
        $this->badGuyDefMod = $badGuyDefMod;
    }
}
