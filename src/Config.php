<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

use Dotenv\Dotenv;
use CommentApp\Exceptions\ConfigException;
use Dotenv\Exception\InvalidPathException;

/**
 * Description of Config
 *
 * @author mrcake
 */
class Config {
    private $observers;
    private $routes;
    private $root;
    private $repoReference;
    
    public function __construct(string $rootDir) {
        if (!file_exists($rootDir) || !is_dir($rootDir)) {
            throw new ConfigException("Not valid path");
        }
        
        try{
            
            $dotenv = new Dotenv($rootDir);
            $dotenv->load();

            $this->root = $rootDir;

            $this->loadRepoConfig();
            $this->loadRouteConfig();
            
        } catch (InvalidPathException $e) {
            throw new ConfigException(
                "Cannot get .env file. Check installation requirements",
                0,
                $e
            );
        }
        
    }
    
    /**
     * 
     * @return type
     */
    public function getMysqlScope()
    {
        return [
            getenv('db_host'),
            getenv('db_dbname'),
            getenv('db_user'),
            getenv('db_password'),
        ];
    }
    
    private function loadRepoConfig()
    {
        $repoConfigPath = $this->root 
            . DIRECTORY_SEPARATOR . 'config' 
            . DIRECTORY_SEPARATOR . 'repositories.php';
        
        $this->repoReference = require $repoConfigPath;
    }
    
    private function loadRouteConfig()
    {
        $routeConfigPath = $this->root 
            . DIRECTORY_SEPARATOR . 'config' 
            . DIRECTORY_SEPARATOR . 'routes.php';
        
        $this->routes = require $routeConfigPath;
    }
    
    /**
     * List of route config
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * Observer config
     *
     * @return type
     */
    public function getObserverReference()
    {
        $observerListPath = $this->root 
            . DIRECTORY_SEPARATOR
            . 'config'
            . DIRECTORY_SEPARATOR
            . 'observer_reference.php';
        
        return $this->observers = require $observerListPath;
    }
    
    public function getRepoClassname($key)
    {
        return $this->repoReference[$key] ?? false;
    }
}
