<?php
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase{
  
    public function testCanAppendSchemaToNamespaceStack(){
        global $translation_namespace_stack,$translation_namespace;
        $translation_namespace_stack = [];
        translator::tlschema('test');
        $this->assertSame($translation_namespace,'test');
    }
}