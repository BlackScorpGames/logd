<?php


use PHPUnit\Framework\TestCase;
require_once __DIR__.'/../lib/output.php';
require_once __DIR__.'/../lib/dbwrapper_array.php';
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
    public function testCanSkipHooksForModules(){
        global  $output,$session,$fetchAssocRows,$translation_namespace_stack,$injected_modules;
        translator::translator_setup();
        $translation_namespace_stack = [];
        $output="empty";
        $session = [
            "user"=>[
                "superuser"=>false
            ]
        ];
        function testHook(){
        return [];
        }
        $fetchAssocRows=[
            [
                'modulename' =>'test',
                'function'=>'testHook'
            ]
        ];
        $injected_modules = [
            0=>[
                "test"=>true
            ]
        ];
        modules::modulehook("test",false,false,"test");
        $this->assertSame("empty",$output);

        $fetchAssocRows = null;
    }
}
