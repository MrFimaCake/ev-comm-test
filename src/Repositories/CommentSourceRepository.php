<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Repositories;

use CommentApp\Models\CommentSource;
use CommentApp\EventManager;
/**
 * Description of CommentSourceRepository
 *
 * @author mrcake
 */
class CommentSourceRepository extends AbstractRepository {
    
    const DEFAULT_SOURCE_TITLE = 'main';
    
    private $table = 'comment_sources';
    
    public function findOrCreateByTitle(string $title)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->connection->prepare("SELECT `id`, `title`, `comment_hash`, `created_at`"
                . " FROM `{$this->table}` WHERE `title`=:title");
        $executed = $statement->execute([':title' => $title]);
        $row = $statement->fetch();
        
        if ($row) {
            return CommentSource::fromArray($row)   ;
        }
        $newRow = new CommentSource;
        $newRow->setAttribute('title', $title);
        $newRow->setAttribute('comment_hash', uniqid(uniqid()));
        
        EventManager::triggerEvent('onSave', $newRow);
        
        $statement = $this->connection->prepare("INSERT INTO `{$this->table}` "
        . " (title, comment_hash, created_at)"
            . " VALUES (:title, :comment_hash, :created_at)");
        
        $statement->execute([
            ':title'        => $newRow->getAttribute('title'),
            ':comment_hash' => $newRow->getAttribute('comment_hash'),
            ':created_at'   => $newRow->getAttribute('created_at'),
        ]);
        
        $newRow->setAttribute('id', $this->connection->lastId());
        return $newRow;
    }
    
    public function findOrCreateHash($hash)
    {
        if ($hash) {
            $statement = $this->connection->prepare("SELECT `id`, `title`, `comment_hash`"
                . " FROM `{$this->table}` WHERE `comment_hash`=':comment_hash'");
                
            $statement->execute([':comment_hash' => $hash]);
            $row = $statement->fetchObject(CommentSource::class);
            if (!$row) {
                return $row;
            }
        }
        
        return $this->findOrCreateByTitle(self::DEFAULT_SOURCE_TITLE);
    }
}
