<?php

namespace LoGD\Core\Test;
use PHPUnit_Framework_TestCase;

class AccountTest extends PHPUnit_Framework_TestCase{

    public function testCanCreate(){
        $createAccount = new CreateAccountInteractor($userRepository,$createAccountValidator);
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