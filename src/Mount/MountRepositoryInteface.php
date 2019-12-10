<?php

namespace blackscorp\logd\Mount;

interface MountRepositoryInteface 
{
    public function getMount(int $mountID=0) : MountEntity;
    
    public function getName(MountEntity $mount);
}
