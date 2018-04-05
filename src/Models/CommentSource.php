<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Models;

/**
 * Description of EventSource
 *
 * @author mrcake
 */
class CommentSource extends Model{
    //put your code here
    protected $id;
    protected $title;
    protected $comment_hash;
    protected $created_at;
    
    public function attributes() : array
    {
        return ['id', 'title', 'comment_hash', 'created_at'];
    }
}
