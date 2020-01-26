<?php


use PHPUnit\Framework\TestCase;
require_once __DIR__.'/../lib/output.php';

class ModulesTest extends TestCase
{
    public function testModuleNotExists(){
        global  $output,$session;
        $output="empty";
        $session = [
            "user"=>[
                "superuser"=>false
            ]
        ];

        modules::modulehook("test");
        $this->assertSame("empty",$output);
    }
    public function testArgumentsConverterToArray(){
        global  $session;
        $session = [
            "user"=>[
                "superuser"=>false
            ]
        ];

        $arguments = modules::modulehook("test",false);
        $this->assertIsArray($arguments);
    }
    public function testArgumentIsAnArrayWithBogus(){
        global  $output,$session;
        $output="empty";
        $session = [
            "user"=>[
                "superuser"=>false
            ]
        ];

        $arguments = modules::modulehook("test","test");


        $this->assertIsArray($arguments);
        $this->assertSame(["bogus_args"=>"test"],$arguments);
        $this->assertSame("empty",$output);
    }
}
