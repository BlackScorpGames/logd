<?php

namespace BlackScorpGames\logd\Mount;

interface MountRepositoryInteface 
{
    public function getMount(int $mountID=0) : MountEntity;
    
    public function getMountName(MountEntity $mount);
}
