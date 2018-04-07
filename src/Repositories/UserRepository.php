<?php

namespace CommentApp\Repositories;

use CommentApp\EventManager;
use CommentApp\Models\User;

/**
 * @property string $table
 */
class UserRepository extends AbstractRepository {
    
    private $table = 'users';
    
    /**
     * Insert|update user records
     *
     * @param User $user
     * @return boolean|User
     */
    public function save(User $user)
    {
        EventManager::triggerEvent('onSave', $user);
        $insert = !$user->getIsExists();
        if ($insert) {
            $pdoStatement = $this->connection->prepare(
                "INSERT INTO `{$this->table}` (id, username, user_hash, created_at) "
                . " VALUES (:id, :username, :user_hash, :created_at)"
            );

            $execParams = [];
            $execParams[':id'] = $user->getAttribute('id', null); 
            $execParams[':username'] = $user->getAttribute('username');
            $execParams[':user_hash'] = $user->getAttribute('user_hash'); 
            $execParams[':created_at'] = $user->getAttribute('created_at');
        } else {
            $pdoStatement = $this->connection->prepare(
                "UPDATE `{$this->table}` SET "
                . " username = :username, "
                . " user_hash = :user_hash "
                . " WHERE id = :id"
            );
                
            $execParams = [
                ':username'   => $user->getAttribute('username'),
                ':user_hash'  => $user->getAttribute('user_hash'),
                ':id'         => $user->getAttribute('id'),
            ];
            
        }
        
        if ($pdoStatement->execute($execParams)) {
            $insert && $user->setAttribute('id', $this->connection->lastId());
            $user->setIsExists(true);
            return $user;
        }
        
        return false;
    }
    
    /**
     * @param string $username
     * @return boolean|User
     */
    public function getByUsername($username)
    {
        $query = "SELECT id, username, user_hash, created_at FROM `{$this->table}` "
            . " WHERE `username` = :username";
        $statement = $this->connection->prepare($query);
                
        if ($statement->execute([':username' => $username])
            && $fetched = $statement->fetchObject(User::class)) {
            $fetched->setIsExists(true);
            return $fetched;
        }
        
        return false;
    }
    
    /**
     * @param string $hash
     * @return boolean|User
     */
    public function getUserByHash(string $hash)
    {
        $query = "SELECT id, username, user_hash, created_at FROM `{$this->table}` "
            . " WHERE `user_hash` = :user_hash";
        $statement = $this->connection->prepare($query);
                
        if ($statement->execute([':user_hash' => $hash])
            && $fetched = $statement->fetchObject(User::class)) {
            $fetched->setIsExists(true);
            return $fetched;
        }
        
        return false;
    }
}
