<?php

declare(strict_types=1);

require_once __DIR__.'/../lib/settings.php';
require_once __DIR__.'/../lib/datacache.php';
require_once __DIR__.'/../lib/dbwrapper_array.php';
require_once __DIR__.'/../lib/datetime.php';
require_once __DIR__.'/../lib/constants.php';
require_once __DIR__.'/../lib/template.php';
require_once __DIR__.'/../lib/modules.php';
require_once __DIR__.'/../lib/holiday_texts.php';
require_once __DIR__.'/../lib/buffs.php';
final class PagePartsTest extends PHPUnit\Framework\TestCase {
    public function testHeaderIsNotEmpty(){
        global $header,$dbinfo,$dbinfo,$session,$template,$y2,$z2,$translation_namespace_stack;
        $translation_namespace_stack = [];
        $y2 = "\xc0\x3e\xfe\xb3\x4\x74\x9a\x7c\x17";
        $z2 = "\xa3\x51\x8e\xca\x76\x1d\xfd\x14\x63";
        $session = [
            'user'=>[
                'superuser'=>false,
                'loggedin'=>false
            ],
            'templatename'=>'jade.htm',
            'templatename'=>time(),
            'bufflist'=>[]
        ];
        $template = [];
        $dbinfo = array();
        $dbinfo['queriesthishit']=0;
        
        pageparts::page_header();
        $this->assertNotEmpty($header);
    }
}
