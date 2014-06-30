<?php

namespace Logd\Core\Response;


abstract class Response {
    public $proceed = false;
    public $failed = false;
    public $errors = array();
} 