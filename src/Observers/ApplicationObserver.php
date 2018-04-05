<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Observers;


use CommentApp\Event;

use ReflectionFunction;
use Closure;
/**
 * Description of ApplicationObserver
 *
 * @author mrcake
 */
class ApplicationObserver {
    //put your code here
    public function runAction(Event $event)
    {
        $app = $event->getSubject();
        $actionClassName = $app->getAction();
        
        $action = new $actionClassName($app);
        $action->run();
    }
}
