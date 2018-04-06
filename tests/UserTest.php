<?php

use CommentApp\Models\User;
use PHPUnit\Framework\TestCase;

use CommentApp\Exceptions\InvalidParamException;

final class UserTest extends TestCase{
    
    public function testThrowExceptionOnInvalidAttribute()
    {
        $this->expectException(InvalidParamException::class);
        
        User::fromArray([uniqid() => 'boom']);
    }
    
    public function testCanBeCreatedFromArray()
    {   
        $this->assertInstanceOf(
            User::class,
            User::fromArray(['username' => 'boom1'])
        );
    }
    
    public function testCanBeUsedAsString()
    {
        $this->assertEquals(
            'uname',
            User::fromArray(['username' => 'uname'])
        );
    }
    
}
