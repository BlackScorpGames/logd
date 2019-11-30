<?php
use PHPUnit\Framework\TestCase;
class HttpTest extends TestCase{
    
    public function testCanReturnFalseOnGET(){
        $result = http::httpget('test');
        $this->assertFalse($result);
    }
    public function testCanReadValueFromGET(){
        $_GET['test'] = 'test';
        
        $result = http::httpget('test');
        $this->assertEquals('test', $result);
    }
}