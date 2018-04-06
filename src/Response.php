<?php

namespace CommentApp;

use CommentApp\Application;
use CommentApp\Exceptions\InvalidParamException;
/**
 * @property Application $app
 * @property array $headers key-value list of headers
 * @property array $view view config
 */
class Response {
    
    private $app;
    private $headers = [];
    
    private $view = [];
    
    private $found;
    
    public function __construct(Application $app) {
        $this->app = $app;
    }
    
    /**
     * @param string $uri where to set Location
     */
    public function addRedirect($uri)
    {
        $this->headers['Location'] = $uri;
    }
    
    /**
     * Return list of headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * 
     * @param string $filename - path from ROOT/views directory
     * @param array $params
     */
    public function setView($filename, array $params = [])
    {
        $this->view['file']   = realpath(__DIR__ . '/../views/' . $filename);
        $this->view['params'] = $params;
    }
    
    /**
     * Send headers and render view
     */
    public function send()
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        $viewFile = $this->view['file'] ?? false;
        
        if ($viewFile) {
            ob_start();

            $viewParams = $this->view['params'];
            extract($viewParams);
            
            require $viewFile;

            $result = ob_get_clean();
            
            echo $result;
        } else {
            throw new InvalidParamException("View file not found");
        }
    }
    
    /**
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }
}
