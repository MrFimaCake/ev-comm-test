<?php

namespace CommentApp\Models;

/**
 * Description of Comment
 *
 * @property int $id
 * @property int $user_id ID of comment author
 * @property int $comment_source_id ID of comment source if we'll need 
 *      more than 1 (ex: comments for different posts)
 * @property string $body Comment body
 * @property string $created_at string representation of date and time creation
 * 
 * @property User $user
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
    
    public static function attributes() : array
    {
        return ['id', 'user_id', 'comment_source_id', 'body', 'created_at'];
    }
}
