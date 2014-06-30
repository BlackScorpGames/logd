<?php


namespace Logd\Core\Interactor;
use Logd\Core\Repository\User as UserRepository;
use Logd\Core\Request\CreateAccount as CreateAccountRequest;
use Logd\Core\Response\CreateAccount as CreateAccountResponse;

class CreateAccount {
    private $userRepository = null;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;

    }
    public function process(CreateAccountRequest $request,CreateAccountResponse $response){

    }
} 