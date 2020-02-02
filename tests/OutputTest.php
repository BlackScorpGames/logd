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

    public function testCanBlockNewNav(){
        global $block_new_navs;
        $block_new_navs = true;
        $result = output::addnav("test");
        $this->assertNull($result);
    }
    public function testIgnoreEmptyTextInNavigation(){
        global $navbysection,$block_new_navs,$navschema;
        $block_new_navs = false;
        $navschema = [];


        output::addnav("test");

        $this->assertArrayHasKey("test",$navbysection);
    }
    public function testNavBySectionArrayIsCreated(){
        global $navbysection,$block_new_navs,$navsection;
        $block_new_navs = false;
        $navbysection = [];
        $navsection ="specialSection";

        output::addnav("test","test.php");
        $this->assertArrayHasKey($navsection,$navbysection);
    }
    public function testNavigationElementCanBeArray(){
        global $navbysection,$block_new_navs,$navsection,$navschema,$translation_namespace;
        $block_new_navs = false;
        $navbysection = [];
        $navsection ="testSection";
        $translation_namespace = "testNamespace";
        $navschema =[];
        output::addnav(["test"],"test.php");

        $this->assertArrayHasKey("test",$navschema);
        $this->assertSame($translation_namespace,$navschema["test"]);
    }
    public function testNavigationArrayHasCorrectValue(){
        global $navbysection,$block_new_navs,$navsection;
        $block_new_navs = false;
        $navsection ="specialSection";
        $navbysection = [
            $navsection=>[]
        ];

        output::addnav("test","test.php");
        $this->assertSame([["test",1=>"test.php","translate"=>false]],$navbysection[$navsection]);
    }
    public function testPrivateAddNavWasCalled(){
        global $block_new_navs,$nav,$blockednavs;
        $blockednavs['blockpartial'] =[];
        $block_new_navs = false;
        output::addnav("","test.php");
        $this->assertEmpty($nav);
    }
}
