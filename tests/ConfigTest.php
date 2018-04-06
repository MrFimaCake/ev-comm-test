<?php

use PHPUnit\Framework\TestCase;
use CommentApp\Exceptions\ConfigException;


final class ConfigTest extends TestCase {
    
    public function testThowsConfigException()
    {
        $this->expectException(ConfigException::class);
        
        new \CommentApp\Config(__DIR__);
    }
}
