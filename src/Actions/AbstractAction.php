<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Actions;

use CommentApp\Application;
/**
 * Description of AbstractAction
 *
 * @author mrcake
 */
abstract class AbstractAction {
    //put your code here
    private $app;
    
    public function __construct(Application $app) {
        $this->app = $app;
    }
    
    public function getApp() : Application
    {
        return $this->app;
    }
    
    abstract public function run();
    
}
