<?php

namespace Logd\Core\Validator;

/**
 * Description of Validator
 *
 * @author BlackScorp<witalimik@web.de>
 */
abstract class Validator
{

    private $errors = array();
    private $object = null;

    public function isValid()
    {
        $this->validate();
        return $this->hasNoErrors();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function attachError($error, $key = null)
    {
        if ($key) {
            $this->errors[$key] = $error;
        } else {
            $this->errors[] = $error;
        }
    }

    protected function setObject($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function hasNoErrors()
    {

        return count($this->errors) === 0;
    }

    abstract protected function validate();
}
