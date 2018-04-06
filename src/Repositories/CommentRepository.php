<?php

namespace CommentApp\Repositories;

use CommentApp\Models\User;
use CommentApp\Models\Comment;
use CommentApp\Models\CommentSource;
use CommentApp\EventManager;

/**
 * @property string $table
 */
class CommentRepository extends AbstractRepository {
    
    private $table = 'comments';
    
    /**
     * 
     * @param string $commentBody
     * @param User $user
     * @param CommentSource $commentSource
     * @return boolean
     */
    public function create(string $commentBody, User $user, CommentSource $commentSource)
    {
        $comment = Comment::fromArray(['body' => $commentBody]);
        EventManager::triggerEvent('onSave', $comment);
        
        $userId = $user->getAttribute('id');
        $commentSourceId = $commentSource->getAttribute('id');
        
        $statement = $this->connection->prepare(
            "INSERT INTO `{$this->table}` (user_id, comment_source_id, body, created_at)"
            . " VALUES (:user_id, :comment_source_id, :body, :created_at)"
        );
        $exec = $statement->execute([
            ':user_id' => $userId,
            ':comment_source_id' => $commentSourceId,
            ':body' => $comment->getAttribute('body'),
            ':created_at' => $comment->getAttribute('created_at'),
        ]);
        
        if ($exec) {
            $comment->setAttribute('id', $this->connection->lastId());
            $comment->setIsExists(true);
            return $comment;
        }
        
        return false;
    }
    
    public function getSourceByHash(string $commentHash)
    {
        $statement = $this->connection->prepare(
            "SELECT `id` FROM `{$this->comment_sources}` WHERE `comment_hash` = :comment_hash"
        );
        if ($statement->execute([':comment_hash' => $commentHash])
            && $row = $statement->fetchRow()) {
            return $row->id;
        }
        
        
        
        $statement = $this->connection->prepare(
            "INSERT INTO `{$this->sourceTable}` (title)"
        );
    }
    
    public function getAll()
    {
        $statement = $this->connection->prepare(
            "SELECT `c`.`id`, `c`.`user_id`, `c`.`body`, `c`.`created_at`,"
            . " `u`.`id` as user__id, `u`.`username` as user__username, `u`.`created_at` as user__created_at"
            . " FROM `{$this->table}` c INNER JOIN `users` u ON  u.id = c.user_id"
        );
            
        if ($statement->execute()) {
            $comments = [];
            $rows = (array)$statement->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($rows as $k => $row) {
                $comment = Comment::fromArray([
                    'id'         => $row['id'],
                    'user_id'    => $row['id'],
                    'body'       => $row['body'],
                    'created_at' => $row['created_at'],
                ]);
                $commentAuthor = User::fromArray([
                    'id'         => $row['user__id'],
                    'username'   => $row['user__username'],
                    'created_at' => $row['user__created_at'],
                ]);
                
                $comment->setUser($commentAuthor);
                $comments[$k] = $comment;
            }
            
            return $comments;
        }
        
        return [];
    }
}
