<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Models;

/**
 * Description of Comment
 *
 * @author mrcake
 */
class Comment extends Model{
    
    protected $id;
    protected $user_id;
    protected $comment_source_id;
    protected $body;
    protected $created_at;
    
    private $user;
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function attributes() : array
    {
        return ['id', 'user_id', 'comment_source_id', 'body', 'created_at'];
    }
}
