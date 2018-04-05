<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Repositories;

/**
 * Description of ObserverRepository
 *
 * @author mrcake
 */
class ObserverRepository extends AbstractRepository{
    //put your code here
    private $table = 'observers';
    
    public function resetList($list)
    {
        $this->clear();
        $this->saveList($list);
    }
    
    public function clear()
    {
        $this->connection->exec("DELETE FROM `{$this->table}`;");
    }
    
    public function getAll()
    {
        $statement = $this->connection->prepare(
            "SELECT `id`, `key`, `priority` FROM `{$this->table}`;"
        );
        
        if ($statement->execute() ) {
            return $statement->fetchAll();
        }
        
        return [];
    }
    
    public function saveList($list)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO {$this->table} (`key`, `priority`, `created_at`)"
            . " VALUES (:key, :priority, :created_at)"
        );
            
        foreach ($list as $row) {
            $statement->execute([
                ':key'        => $row['key'],
                ':priority'   => $row['priority'],
                ':created_at' => (new \DateTime("now"))->format('Y-m-d H:m:i'),
            ]);
        }
    }
}
