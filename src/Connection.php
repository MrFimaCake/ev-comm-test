<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;


use PDO;
/**
 * Description of Connection
 *
 * @author mrcake
 */
class Connection {
    
    private $pdo;
    
    public function __construct(Config $config) {
        list($host, $dbName, $uname, $upass) = $config->getMysqlScope();
        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $dbName);
        $this->pdo = new PDO($dsn , $uname, $upass);
    }
    
    public function query($query)
    {
        return $this->pdo->query($query);
    }
    
    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }
    
    public function prepare($statement) : \PDOStatement
    {
        return $this->pdo->prepare($statement);
    }
    
    public function exec($statement)
    {
        return $this->pdo->exec($statement);
    }
    
    public function lastId()
    {
        return $this->pdo->lastInsertId();
    }
}
