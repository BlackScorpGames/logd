<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../lib/sanitize.php';
class TranslatorTest extends TestCase{
  
    public function testCanAppendSchemaToNamespaceStack(){
        global $translation_namespace_stack,$translation_namespace;
        $translation_namespace_stack = [];
        translator::tlschema('test');
        $this->assertSame($translation_namespace,'test');
    }
    public function testCanGetLastEntryFromStack(){
        global $translation_namespace_stack,$translation_namespace;
        $translation_namespace_stack = [
            'test'
        ];
        translator::tlschema(false);
        $this->assertSame('test', $translation_namespace);
    }
    public function testCanGetNamespaceFromRequestURI(){
        global $translation_namespace_stack,$translation_namespace,$REQUEST_URI;
        
        $translation_namespace_stack = [];
        $REQUEST_URI = 'test.php';
        translator::tlschema(false);
        
        $this->assertSame('test.php', $translation_namespace);
    }
    public function testCanGetRemoveQuestionMarkFromRequestUri(){
            global $translation_namespace_stack,$translation_namespace,$REQUEST_URI;
        
        $translation_namespace_stack = [];
        $REQUEST_URI = 'test.php?';
        translator::tlschema(false);
        
        $this->assertSame('test.php', $translation_namespace);
    }
}