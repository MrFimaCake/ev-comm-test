<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Models;

use CommentApp\EventManager;
/**
 * Description of User
 *
 * @author mrcake
 */
class User extends Model{
    
    protected $id;
    protected $username;
    protected $user_hash;
    protected $created_at;
    
    
    public function __toString() {
        return $this->getAttribute('username');
    }


    public function setUsername(string $username)
    {
        $this->setAttribute('username', $username);
    }
    
    public function attributes() :array
    {
        return ['id', 'username', 'user_hash', 'created_at'];
    }
    
    public function setIsExists(bool $exists)
    {
        $this->exists = $exists;
    }
}
