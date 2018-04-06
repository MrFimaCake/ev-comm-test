<?php

namespace CommentApp\Models;

use CommentApp\Exceptions\InvalidParamException;

abstract class Model {
    
    private $exists = false;
    
    /**
     * Get if current model corresponds to existing record
     *
     * @return bool
     */
    public function getIsExists()
    {
        return $this->exists;
    }
    
    /**
     * Set if current model corresponds to existing record
     * 
     * @param bool $exist
     */
    public function setIsExists(bool $exist)
    {
        $this->exists = $exist;
    }
    
    /**
     * List of existing for attributes
     */
    abstract public static function attributes() : array;
    
    /**
     * Get all attributes in key-value array
     * @return array
     */
    public function getAttributes()
    {
        $attrKeys = static::attributes();
        $attrs = [];
        foreach ($attrKeys as $attrKey) {
            $attrs[$attrKey] = $this->{$attrKey};
        }
        return $attrs;
    }
    
    /**
     * 
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return property_exists($this, $name);
    }
    
    /**
     * Set value to model attribute
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        $this->{$name} = $value;
    }
    
    /**
     * @param string $name Key of attribute
     * @param mixed $onNull Default value if no $name param
     * @return type
     */
    public function getAttribute($name, $onNull = false)
    {
        return $this->{$name} ?? $onNull;
    }
    
    /**
     * Create new instance by array. Used in repository
     *
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes)
    {
        $newSelf = new static;
        $selfAttributes = static::attributes();
        foreach ($attributes as $prop => $value) {
            if (!in_array($prop, $selfAttributes)) {
                throw new InvalidParamException("Invalid attribute");
            }
            $newSelf->setAttribute($prop, $value);
        }
        
        if ($newSelf->hasAttribute('id') && $newSelf->getAttribute('id')) {
            $newSelf->setIsExists(true);
        }
        
        return $newSelf;
    }
}
