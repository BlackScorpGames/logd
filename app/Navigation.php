<?php
/**
 * Created by PhpStorm.
 * User: BlackScorp
 * Date: 02.07.14
 * Time: 21:05
 */

namespace Logd\Core\App;


class Navigation {
    public  $position = 0;
    public $text = '';
    public $active = false;
    public $link = '';
    public function __construct($text)
    {
        $this->text = $text;
    }


} 