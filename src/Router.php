<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;


class Router {
    
    private $app;
    private $routes;
    
    /**
     * @param \CommentApp\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->routes = $app->getConfig()->getRoutes();
    }
    
    /**
     * 
     * @param \CommentApp\Request $request
     * @param \CommentApp\Response $response
     */
    public function processRequest(Request $request, Response $response)
    {
        
        foreach ($this->routes as $rule) {
            if (!$request->matchMethod($rule['method'])
                || !$request->matchUrl($rule['url'])
                || !$request->matchParams($rule['params'] ?? [])) {
                continue;
            }
            
            if (!$request->matchUser($rule['authOnly'] ?? false)) {
//                echo '<pre>';die(var_dump(
//                    __METHOD__, 
//                    $rule['authOnly'] ?? false,
//                    $rule,
//                    $request->getIsUserGuest(),
//                    $request->getUser(),
//                    __LINE__//, 
//        //            $request->getIsUserGuest(), 
//        //            $request->getUser() 
//                ));
                continue;
            }
            
            $response->setFound(true);
            $action = $rule['action'];
            
            $this->app->setAction($action);
            
            EventManager::triggerEvent('runAction', $this->app);
            return;
        }
        
        echo '<pre>';die(var_dump(
            __METHOD__, 
            __LINE__//, 
//            $request->getIsUserGuest(), 
//            $request->getUser() 
        ));
        
        
        $response->addRedirect('/');
        $response->send();
        
    }
}
