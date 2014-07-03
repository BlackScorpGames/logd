<?php
namespace Logd\Core\App\Validator;
use Logd\Core\Validator\CreateAccount as CreateAccountValidator;

class CreateAccount extends CreateAccountValidator{

    protected function validate()
    {
        $this->checkUsername();
        $this->checkPassword();
        if($this->emailRequired){
            $this->checkEmail();
        }
    }
    private function isEmpty($value){
        return in_array($value,array(null,'',false));
    }
    private function checkUsername(){
        if($this->isEmpty($this->username)){
            $this->attachError('username is empty');
        }
        if(strlen($this->username) < 3){
            $this->attachError('username is short');
        }
        if(strlen($this->username) > 25){
            $this->attachError('username is long');
        }
        if(!$this->uniqueUsername){
            $this->attachError('username exists');
        }
    }
    private function checkPassword(){
        if($this->isEmpty($this->password)){
            $this->attachError('password is empty');
        }
        if(strlen($this->password) < 3 ){
            $this->attachError('password is too short');
        }
        if($this->password !== $this->passwordConfirm){
            $this->attachError('password confirm does not match');
        }
    }
    private function checkEmail(){
        if($this->isEmpty($this->email)){
            $this->attachError('email is empty');
        }
        if(!$this->uniqueEmail){
            $this->attachError('email is used');
        }
        if(!((bool)filter_var($this->email, FILTER_VALIDATE_EMAIL))){
           $this->attachError('email is invalid');
        }
    }
} 