<?php

namespace blackscorp\logd\Mount;

class MountBuff implements MountBuffEntity
{
    private $name               =   '';
    
    private $roundmsg           =   '';
    
    private $wearoff            =   '';
    
    private $effectmsg          =   '';
    
    private $effectnodmgmsg     =   '';
    
    private $effectfailmsg      =   '';
    
    private $rounds             =   0;
    
    private $atkmod             =   '';
    
    private $defmod             =   '';
    
    private $invulnerable       = '';
    
    private $regen              =   '';
    
    private $minioncount        =   '';
    
    private $minbadguydamage    =   '';
    
    private $maxbadguydamage    =   '';
    
    private $mingoodguydamage   =   '';
    
    private $maxgoodguydamage   =   '';
    
    private $liferap            =   '';
    
    private $damageshield       =   '';
    
    private $badguydmgmod       =   '';
    
    private $badguyatkmod       =   '';
    
    private $badguydefmod       =   '';
            
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
        return $this->roundmsg;
    }

    public function getWearoff() : string 
    {
        return $this->wearoff;
    }

    public function getEffectMsg() : string 
    {
        return $this->effectmsg;
    }

    public function getEffectNoDmgMsg() : string 
    {
        return $this->effectnodmgmsg;
    }

    public function getEffectFailMsg() : string 
    {
        return $this->effectfailmsg;
    }

    public function getRounds() : int 
    {
        return $this->rounds;
    }

    public function getAtkMod() : string
    {
        return $this->atkmod;
    }

    public function getDefMod() :string 
    {
        return $this->defmod;
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
        return $this->minioncount;
    }

    public function getMinBadGuyDamage() : string 
    {
        return $this->minbadguydamage;
    }

    public function getMaxBadGuyDamage() : string 
    {
        return $this->maxbadguydamage;
    }

    public function getMinGoodGuyDamage() : string 
    {
        return $this->mingoodguydamage;
    }

    public function getMaxGoodGuyDamage() : string 
    {
        return $this->maxgoodguydamage;
    }

    public function getLifeTap() : string 
    {
        return $this->liferap;
    }

    public function getDamageShield() : string 
    {
        return $this->damageshield;
    }

    public function getBadGuyDmgMod() : string 
    {
        return $this->badguydmgmod;
    }

    public function getBadGuyAtkMod() : string 
    {
        return $this->badguyatkmod;
    }

    public function getBadGuyDefMod() : string 
    {
        return $this->badguydefmod;
    }

    public function setName(string $name) : void 
    {
        $this->name = $name;
    }

    public function setRoundMsg(string $roundMsg) : void 
    {
        $this->roundmsg = $roundMsg;
    }

    public function setWearoff(string $wearoff) : void 
    {
        $this->wearoff = $wearoff;
    }

    public function setEffectMsg(string $effectMsg) : void 
    {
        $this->effectmsg = $effectMsg;
    }

    public function setEffectNoDmgMsg(string $effectNoDmgMsg) : void 
    {
        $this->effectnodmgmsg = $effectNoDmgMsg;
    }

    public function setEffectFailMsg(string $effectFailMsg) : void 
    {
        $this->effectfailmsg = $effectFailMsg;
    }

    public function setRounds(int $rounds) : void 
    {
        $this->rounds = $rounds;
    }

    public function setAtkMod(string $atkMod) : void 
    {
        $this->atkmod = $atkMod;
    }

    public function setDefMod(string $defMod) : void 
    {
        $this->defmod = $defMod;
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
        $this->minioncount = $minionCount;
    }

    public function setMinBadGuyDamage(string $minBadGuyDamage) : void 
    {
        $this->minbadguydamage = $minBadGuyDamage;
    }

    public function setMaxBadGuyDamage(string $maxBadGuyDamage) : void 
    {
        $this->maxbadguydamage = $maxBadGuyDamage;
    }

    public function setMinGoodGuyDamage(string $minGoodGuyDamage) : void 
    {
        $this->mingoodguydamage = $minGoodGuyDamage;
    }

    public function setMaxGoodGuyDamage(string $maxGoodGuyDamage) : void 
    {
        $this->maxgoodguydamage = $maxGoodGuyDamage;
    }

    public function setLifeTap(string $lifeTap) : void 
    {
        $this->liferap = $lifeTap;
    }

    public function setDamageShield(string $damageShield) : void 
    {
        $this->damageshield = $damageShield;
    }

    public function setBadGuyDmgMod(string $badGuyDmgMod) : void 
    {
        $this->badguydmgmod = $badGuyDmgMod;
    }

    public function setBadGuyAtkMod(string $badGuyAtkMod) : void 
    {
        $this->badguyatkmod = $badGuyAtkMod;
    }

    public function setBadGuyDefMod(string $badGuyDefMod) : void 
    {
        $this->badguydefmod = $badGuyDefMod;
    }
}
