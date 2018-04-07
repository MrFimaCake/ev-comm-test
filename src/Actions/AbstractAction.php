<?php

namespace CommentApp\Actions;

use CommentApp\Application;

/**
 * Base class for every standalone action
 * 
 * @property Application $app provides access to application object
 */
abstract class AbstractAction {
    
    private $app;

    /** 
     * @param Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }
    
    /**
     * @return Application
     */
    public function getApp() : Application
    {
        return $this->app;
    }
    
    /**
     * Executes logic which for called action was created
     * 
     * @return void
     */
    abstract public function run();
}
