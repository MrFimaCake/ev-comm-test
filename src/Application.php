<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

/**
 * Description of Application
 *
 * @author mrcake
 */
class Application {

    private $action;
    private $config;
    private $connection;
    
    private $request;
    private $response;
    
    public function __construct(Config $config) {
        
        $this->config = $config;
        $this->connection = new Connection($this->config);
        
        EventManager::initEventManager($this);
    }
    
    public function createResponse(Request $request) : Response
    {
        $response = new Response($this);
        $this->response = $response;
        $this->request = $request;
        
        
        EventManager::triggerEvent('onRequestInit', $request);
        EventManager::triggerEvent('initApp', $this);
        
        $router = new Router($this);
        $router->processRequest($request, $response);
                
        return $response;
    }
    
    public function getRepo($subject)
    {
        $subjectKey = is_object($subject) ? get_class($subject) : $subject;
        $repoClassname = $this->config->getRepoClassname($subjectKey);
        if ($repoClassname) {
            $repoReflection = new \ReflectionClass($repoClassname);
            return $repoReflection->newInstance($this->connection);
        }
        return false;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function getUser()
    {
        return $this->request->getUser();
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    public function getAction()
    {
        return $this->action;
    }
}
