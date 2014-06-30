<?php

namespace LoGD\Core\Test;
use LoGD\Core\App\Repository\PDOUser;
use PHPUnit_Framework_TestCase;
use Logd\Core\Interactor\CreateAccount as CreateAccountInteractor;
use Logd\Core\Request\CreateAccount as CreateAccountRequest;
use Logd\Core\Response\CreateAccount as CreateAccountResponse;
use LoGD\Core\App\Repository\PDOUser as UserRepository;



class AccountTest extends PHPUnit_Framework_TestCase{

    public function testCanCreate(){
        require_once __DIR__.'/../bootstrap.php';
        $userRepository = new UserRepository($pdo);
        $createAccount = new CreateAccountInteractor($userRepository);
        $request = new CreateAccountRequest(
            'TestUsername',
            'TestPassword',
            'TestPassword'
        );
        $response = new CreateAccountResponse();
        $createAccount->process($request,$response);
        $this->assertFalse($response->failed);
    }

} 