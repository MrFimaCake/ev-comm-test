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
    
    /**
     * @return type
     */
    public function getAll()
    {
        $statement = $this->connection->prepare(
            "SELECT `id`, `observer_key` FROM `{$this->table}`;"
        );
        
        if ($statement->execute()) {
            return $statement->fetchAll();
        }
        
        return [];
    }
    
    /**
     * Write each observer in db
     * @param array $list
     */
    public function saveList(array $list)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO {$this->table} (`observer_key`, `created_at`)"
            . " VALUES (:key, :created_at)"
        );
            
        foreach ($list as $row) {
            $res = $statement->execute([
                ':key'        => $row,
                ':created_at' => (new \DateTime("now"))->format('Y-m-d H:m:i'),
            ]);
            if (!$res) {
                $err = $this->connection->errorInfo();
                echo end($err), "\n";
            }
        }
    }
}
