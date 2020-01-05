<?php

declare(strict_types=1);

final class OutputTest extends \PHPUnit\Framework\TestCase{
    
    public function testBlockNewOutputIsWorking(){
        global $block_new_output;
        $block_new_output = true;
        
        $result = output::doOutput("test");
        $this->assertEmpty($result);
    }
    public function testOutputWithArrayParameters(){
        global $output,$translation_namespace_stack,$session,$settings,$block_new_output;
        $translation_namespace_stack = [];
        $block_new_output = false;
        $settings=[
            "enabletranslation"=>false
        ];
          $session = [
            'user'=>[
                'superuser'=>false,
                'loggedin'=>false
            ],
            'templatename'=>'jade.htm',
            'templatename'=>time(),
            'bufflist'=>[]
        ];
        $output ="";
        output::doOutput(["test"]);
        
        $this->assertSame("test\n", $output);
    }
    public function testOutputWithArrayAndNamespace(){
              global $output,$translation_namespace_stack,$session,$settings,$block_new_output;
        $translation_namespace_stack = [
         "testNamespace"
        ];
        $block_new_output = false;
        $settings=[
            "enabletranslation"=>false
        ];
          $session = [
            'user'=>[
                'superuser'=>false,
                'loggedin'=>false
            ],
            'templatename'=>'jade.htm',
            'templatename'=>time(),
            'bufflist'=>[]
        ];
        $output ="";
        output::doOutput([true,"testNamespace","test"]);
        
        $this->assertSame("test\n", $output);
    }
}
