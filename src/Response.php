<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

use CommentApp\Application;
/**
 * Description of Response
 *
 * @author mrcake
 */
class Response {
    //put your code here
    
    private $app;
    private $headers = [];
    
    private $view = [];
    
    private $found;
    
    public function __construct(Application $app) {
        $this->app = $app;
    }
    
    public function addRedirect($uri)
    {
        $this->headers['Location'] = $uri;
    }
    
    public function setView($filename, array $params = [])
    {
        $this->view['file']   = realpath(__DIR__ . '/../views/' . $filename);
        $this->view['params'] = $params;
    }
    
    public function send()
    {
        //send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        //render view
        $viewFile = $this->view['file'] ?? false;
        
        if ($viewFile) {
            ob_start();

            $viewParams = $this->view['params'];
            extract($viewParams);
            
            require $viewFile;

            $result = ob_get_clean();
            
            echo $result;
        }
    }
    
    public function setFound(bool $found)
    {
        $this->found = $found;
    }
    
    public function getFound()
    {
        return $this->found;
    }
    
    public function getApp()
    {
        return $this->app;
    }
}
