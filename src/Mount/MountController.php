<?php

namespace blackscorp\logd\Mount;


class MountController 
{

    private $mountRepository = null;
    
    public function __construct(MountRepository $mountRepository) 
    {
        $this->mountRepository = $mountRepository;
    }



    public function deactivateMount(MountEntity $mount)
    {
        
    }
    
    
    public function defaultAction()
    {
        
    }
}
