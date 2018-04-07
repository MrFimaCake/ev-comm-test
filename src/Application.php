<?php

namespace CommentApp;

use CommentApp\Exceptions\CommentAppException;
use CommentApp\Exceptions\NotFoundException;
use CommentApp\Exceptions\ConfigException;

/**
 * CommentApp application object
 * 
 * @property string          $action Class name of run action
 * @property Config          $config config instance
 * @property Connection      $connection connection instance
 * @property ConfigException $exception if something is wrong on app creation
 * @property Request         $request
 * @property Response        $response
 */
class Application {

    private $action;
    private $config;
    private $connection;
    
    private $exception;
    
    private $request;
    private $response;
    
    public function __construct(Config $config) {
        
        $this->config = $config;
        try{
            $this->connection = new Connection($this->config);
            
            EventManager::initEventManager($this);
            EventManager::triggerEvent('initApp', $this);
            
        } catch (ConfigException $e) {
            $this->exception = $e;
        }
        
        
    }
    
    /**
     * @param \CommentApp\Request $request
     * @return \CommentApp\Response
     */
    public function createResponse(Request $request) : Response
    {
        
        $response = new Response($this);
        
        if ($this->exception) {
            $response->setView('error.php', [
                'errorMessage' => $this->exception->getMessage()
            ]);
            return $response;
        }
        
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
    
    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Class name of action selected by route matching
     * 
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
