<?php

namespace CommentApp\Models;

/**
 * Source of comment
 * 
 * @property int $id
 * @property string $title
 * @property string $comment_hash for using in client
 * @property string $created_at string representation of date and time creation
 */
class CommentSource extends Model{
    
    protected $id;
    protected $title;
    protected $comment_hash;
    protected $created_at;
    
    public static function attributes() : array
    {
        return ['id', 'title', 'comment_hash', 'created_at'];
    }
}
