<?php
/**
 * Created by PhpStorm.
 * User: vitalij.mik
 * Date: 30.06.14
 * Time: 14:18
 */

namespace Logd\Core;


class Env {
    const TEST = 'test';
    const DEVELOP = 'develop';
    const PRODUCTION = 'production';
    private static $env = null;
    private static function loadFromGlobals(){
        if(isset( $_SERVER['REMOTE_ADDR'])){
            self::$env = (!filter_var(
                $_SERVER['REMOTE_ADDR'],
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) ? 'develop' : 'production');
        }


        if(isset( $_ENV['env']) && $_ENV['env'] === Env::TEST){
            self::$env = Env::TEST;
        }
    }
    public static function isTest(){
        if(!self::$env){
            self::loadFromGlobals();
        }
        return self::$env === Env::TEST;
    }
    public static function isDevelop(){
        if(!self::$env){
            self::loadFromGlobals();
        }
        return self::$env === Env::DEVELOP;
    }
    public static function isProduction(){
        if(!self::$env){
            self::loadFromGlobals();
        }
        return self::$env === Env::PRODUCTION;
    }

} 