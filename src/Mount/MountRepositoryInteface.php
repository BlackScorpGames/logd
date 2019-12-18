<?php

namespace blackscorp\logd\Mount;

interface MountRepositoryInteface 
{
    public function findMount(int $mountID=0) : MountEntity;
    
    public function findName(MountEntity $mount);
}
