<?php

namespace LoGD\Core\Test;

use PHPUnit_Framework_TestCase;
use Logd\Core\Interactor\CreateAccount as CreateAccountInteractor;
use Logd\Core\Request\CreateAccount as CreateAccountRequest;
use Logd\Core\Response\CreateAccount as CreateAccountResponse;
use Logd\Core\App\Repository\PDOUser as UserRepository;
use Logd\Core\App\Validator\CreateAccount as Validator;


class AccountCreateTest extends PHPUnit_Framework_TestCase{
    private $userRepository = null;
    private $validator = null;
    public function setUp(){
        require __DIR__.'/../bootstrap.php';
        $this->userRepository = new UserRepository($pdo);
        $this->validator = new Validator();
    }
    /**
     * @param CreateAccountRequest $request
     *
     * @return CreateAccountResponse
     */
    private function execute(CreateAccountRequest $request){

        $createAccount = new CreateAccountInteractor($this->userRepository,$this->validator);

        $response = new CreateAccountResponse();
        $createAccount->process($request,$response);
        return $response;
    }
    public function testSuccessfull(){
        $request = new CreateAccountRequest(
            'TestUsername',
            'TestPassword',
            'TestPassword',
            'male'
        );
        $response = $this->execute($request);
        $this->assertFalse($response->failed);
    }

    public function testPasswordShort(){
        $request = new CreateAccountRequest(
            'TestUsername',
            '123',
            '123',
            'male'
        );
       
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
} 