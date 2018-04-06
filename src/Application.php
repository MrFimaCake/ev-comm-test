<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

use CommentApp\Exceptions\CommentAppException;
use CommentApp\Exceptions\NotFoundException;

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
        EventManager::triggerEvent('initApp', $this);
    }
    
    /**
     * @param \CommentApp\Request $request
     * @return \CommentApp\Response
     */
    public function createResponse(Request $request) : Response
    {
        $response = new Response($this);
        try {
            $this->response = $response;
            $this->request = $request;

            EventManager::triggerEvent('onRequestInit', $request);

            $router = new Router($this);
            $found = $router->processRequest($request, $response);

            if ($found === false) {
                throw new NotFoundException("Action ot found", 404);
            }
        } catch (CommentAppException $e) {
            if ($e->getCode() === 404) {
                http_response_code(404);
            }
            $response->setView('error.php', ['errorMessage' => $e->getMessage()]);
        } finally {
                
            return $response;
        }
    }
    
    /**
     * @param string $subject class name of model
     * @return mixed
     */
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
    
    /**
     * @return \CommentApp\Config
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * @return \CommentApp\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * @return \CommentApp\Models\User
     */
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
