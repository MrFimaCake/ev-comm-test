<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Models;

use CommentApp\EventManager;
use CommentApp\Observers\DateTimeObserver;
/**
 * Description of Model
 *
 * @author mrcake
 */
abstract class Model {
    //put your code here
    private $exists = false;
    
    public function __construct() {
        EventManager::attachObserver(static::class, DateTimeObserver::class);
    }
    
    public function __get($name) {
        return $this->hasAttribute($name) ? $this->getAttribute($name) : false;
    }
    
    public function __set($name, $value) {
        $this->hasAttribute($name) && $this->setAttribute($name, $value);
    }
    
    public function getIsExists()
    {
        return $this->exists;
    }
    
    public function setIsExists(bool $exist)
    {
        $this->exists = $exist;
    }
    
    abstract public function attributes() : array;
    
    public function getAttributes()
    {
        $attrKeys = $this->attributes();
        $attrs = [];
        foreach ($attrKeys as $attrKey) {
            $attrs[$attrKey] = $this->{$attrKey};
        }
        return $attrs;
    }
    
    public function hasAttribute($name)
    {
        return property_exists($this, $name);
    }
    
    public function setAttribute($name, $value)
    {
        $this->{$name} = $value;
    }
    
    public function getAttribute($name, $onNull = false)
    {
        return $this->{$name} ?? $onNull;
    }
    
    public static function fromArray(array $attributes)
    {
        $newSelf = new static;
        foreach ($attributes as $prop => $value) {
            $newSelf->setAttribute($prop, $value);
        }
        
        if ($newSelf->hasAttribute('id') && $newSelf->getAttribute('id')) {
            $newSelf->setIsExists(true);
        }
        
        return $newSelf;
    }
}
