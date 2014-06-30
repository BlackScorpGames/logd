<?php


namespace Logd\Core\Interactor;
use Logd\Core\Repository\User as UserRepository;
use Logd\Core\Request\CreateAccount as Request;
use Logd\Core\Response\CreateAccount as Response;
use Logd\Core\Validator\CreateAccount as Validator;
class CreateAccount {
    private $userRepository = null;
    private $validator = null;
    public function __construct(UserRepository $userRepository,Validator $validator){
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }
    private function setValidatorValues(Request $request){
        $this->validator->username = $request->getUsername();
        $this->validator->password = $request->getPassword();
        $this->validator->passwordConfirm = $request->getPasswordConfirm();
        $this->validator->gender = $request->getGender();
        $this->validator->uniqueEmail = !$this->userRepository->emailExists($request->getEmail());
        $this->validator->uniqueUsername = !$this->userRepository->usernameExists($request->getUsername());
        $this->validator->email = $request->getEmail();
    }
    private function setResponseValues(Request $request,Response $response){
        $response->proceed = true;
        $response->username = $request->getUsername();
        $response->password = $request->getPassword();
        $response->passwordConfirm = $request->getPasswordConfirm();
        $response->email = $request->getEmail();
        $response->gender = $request->getGender();
    }
    public function process(Request $request,Response $response){
        $this->setResponseValues($request,$response);
        $this->setValidatorValues($request);

        if(!$this->validator->isValid()){
            $response->failed = true;
            $response->errors = $this->validator->getErrors();
            return;
        }

    }
} 