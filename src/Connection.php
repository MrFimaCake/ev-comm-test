<?php


namespace CommentApp;


use PDO;

use CommentApp\Exceptions\ConfigException;
use PDOException;

class Connection {
    
    private $pdo;
    
    public function __construct(Config $config) {
        list($host, $dbName, $uname, $upass) = $config->getMysqlScope();
        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $dbName);
        try{
            $this->pdo = new PDO($dsn , $uname, $upass);
        } catch (PDOException $e) {
            throw new ConfigException("Configuration config", 0, $e);
        }
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
