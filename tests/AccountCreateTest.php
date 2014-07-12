<?php

namespace LoGD\Core\Test;

use PHPUnit_Framework_TestCase;
use Logd\Core\Interactor\CreateAccount as CreateAccountInteractor;
use Logd\Core\Request\CreateAccount as CreateAccountRequest;
use Logd\Core\Response\CreateAccount as CreateAccountResponse;
use Logd\Core\Mock\Repository\User as UserRepository;
use Logd\Core\App\Validator\CreateAccount as Validator;
use Logd\Core\App\Service\BcrypPasswordHasher as PasswordHasher;
use Logd\Core\Entity\User as UserEntity;

class AccountCreateTest extends PHPUnit_Framework_TestCase{
    /**
     * @var UserRepository
     */
    private $userRepository = null;
    private $validator = null;
    private $passwordHasher = null;
    public function setUp(){
        $users = array();
        $user =  new UserEntity(1,'Dummy','123456');
        $user->setEmail('dummy@test.com');
        $users[]=$user;
        $this->userRepository = new UserRepository($users);
        $this->validator = new Validator();
        $this->passwordHasher = new PasswordHasher;
    }
    /**
     * @param CreateAccountRequest $request
     *
     * @return CreateAccountResponse
     */
    private function execute(CreateAccountRequest $request){
        $createAccount = new CreateAccountInteractor($this->userRepository,$this->validator,$this->passwordHasher);
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
        $this->assertNotNull($this->userRepository->findByUsername('TestUsername'));
    }
    public function testSuccessfullWithRequiredEmail(){
        $request = new CreateAccountRequest(
            'TestUsername',
            'TestPassword',
            'TestPassword',
            'male'
        );
        $request->setEmail('test@test.com');
        $request->setEmailRequired(true);

        $response = $this->execute($request);
        $this->assertFalse($response->failed);
    }
    public function testPasswordTooShort(){
        $request = new CreateAccountRequest(
            'TestUsername',
            '12',
            '12',
            'male'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testPasswordTooLong(){
        $longUsername = implode('',array_fill(0,26,'a'));

        $request = new CreateAccountRequest(
            $longUsername,
            '123456',
            '123456',
            'male'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testWrongPasswordConfirm(){
        $request = new CreateAccountRequest(
            'TestUsername',
            '123456',
            '12345',
            'male'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testEmptyUsername(){
        $request = new CreateAccountRequest(
            '',
            '123456',
            '123456',
            'male'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testUsernameExists(){
        $request = new CreateAccountRequest(
            'Dummy',
            '123456',
            '123456',
            'male'
        );
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testEmailExistsRequired(){
        $request = new CreateAccountRequest(
            'TestUser',
            '123456',
            '123456',
            'male'
        );
        $request->setEmailRequired(true);
        $request->setEmail('dummy@test.com');
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testEmptyNotRequiredEmail(){
        $request = new CreateAccountRequest(
            'TestUser',
            '123456',
            '123456',
            'male'
        );
        $request->setEmail('');
        $response = $this->execute($request);

        $this->assertFalse($response->failed);
    }
    public function testInvalidNotRequiredEmail(){
        $request = new CreateAccountRequest(
            'TestUser',
            '123456',
            '123456',
            'male'
        );
        $request->setEmail('foo');
        $response = $this->execute($request);
        $this->assertFalse($response->failed);
    }
    public function testEmptyRequiredEmail(){
        $request = new CreateAccountRequest(
            'TestUser',
            '123456',
            '123456',
            'male'
        );
        $request->setEmail('');
        $request->setEmailRequired(true);
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
    public function testInvalidRequiredEmail(){
        $request = new CreateAccountRequest(
            'TestUser',
            '123456',
            '123456',
            'male'
        );
        $request->setEmail('foo');
        $request->setEmailRequired(true);
        $response = $this->execute($request);
        $this->assertTrue($response->failed);
    }
} 