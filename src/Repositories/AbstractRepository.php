<?php

namespace CommentApp\Repositories;

use CommentApp\Connection;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractRepository
 *
 * @author mrcake
 */
abstract class AbstractRepository {
    
    protected $connection;
    
    //put your code here
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
}
