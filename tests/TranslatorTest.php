<?php
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase{
    public function testCanAppendSchemaToNamespaceStack(){
        global $translation_namespace,$translation_namespace;
        translator::tlschema('test');
        $this->assetSame($translation_namespace,'test');
    }
}