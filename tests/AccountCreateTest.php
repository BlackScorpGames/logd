<?php

namespace LoGD\Core\Test;

use PHPUnit_Framework_TestCase;
use Logd\Core\Interactor\CreateAccount as CreateAccountInteractor;
use Logd\Core\Request\CreateAccount as CreateAccountRequest;
use Logd\Core\Response\CreateAccount as CreateAccountResponse;
use Logd\Core\App\Repository\PDOUser as UserRepository;



class AccountCreateTest extends PHPUnit_Framework_TestCase{
    private $userRepository = null;
    public function setUp(){
        require __DIR__.'/../bootstrap.php';
        $this->userRepository = new UserRepository($pdo);
    }
    /**
     * @param CreateAccountRequest $request
     *
     * @return CreateAccountResponse
     */
    private function execute(CreateAccountRequest $request){

        $createAccount = new CreateAccountInteractor($this->userRepository);

        $response = new CreateAccountResponse();
        $createAccount->process($request,$response);
        return $response;
    }
    public function testSuccessfull(){
        $request = new CreateAccountRequest(
            'TestUsername',
            'TestPassword',
            'TestPassword'
        );
        $response = $this->execute($request);
        $this->assertFalse($response->failed);
    }

    public function testPasswordShort(){
        $request = new CreateAccountRequest(
            'TestUsername',
            '123',
            '123'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
} 