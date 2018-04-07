<?php


namespace CommentApp;


use PDO;

use CommentApp\Exceptions\ConfigException;
use PDOException;

/**
 * @property PDO $pdo;
 */

class Connection {
    
    private $pdo;
    
    /**
     * @param \CommentApp\Config $config
     * @throws ConfigException
     */
    public function __construct(Config $config) {
        list($host, $dbName, $uname, $upass) = $config->getMysqlScope();
        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $dbName);
        try{
            $this->pdo = new PDO($dsn , $uname, $upass);
        } catch (PDOException $e) {
            throw new ConfigException("DB configuration error", 0, $e);
        }
    }
    
    /**
     * @param string $query
     *
     * @return PDOStatement
     */
    public function query($query)
    {
        return $this->pdo->query($query);
    }
    
    /**
     * Error of last execution
     *
     * @return array
     */
    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }
    
    /**
     * Prepares given query
     * 
     * @param string $statement
     * @return \PDOStatement
     */
    public function prepare($statement) : \PDOStatement
    {
        return $this->pdo->prepare($statement);
    }
    
    /**
     * Calls PDO::exec() with query string
     *
     * @param string $statement
     * @return int
     */
    public function exec($statement)
    {
        return $this->pdo->exec($statement);
    }
    
    /**
     * PDO last insert ID
     * @return string
     */
    public function lastId()
    {
        return $this->pdo->lastInsertId();
    }
}
