<?php

namespace CommentApp\Repositories;

use CommentApp\Connection;
/**
 * Base db repository
 *
 * @property Connection $connection 
 */
abstract class AbstractRepository {
    
    protected $connection;
    
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
}
