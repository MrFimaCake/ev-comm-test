<?php

namespace CommentApp\Observers;


use CommentApp\Event;

/**
 * Observes application events
 */
class ApplicationObserver {
    
    /**
     * @param Event $event
     */
    public function runAction(Event $event)
    {
        $app = $event->getSubject();
        $actionClassName = $app->getAction();
        
        $action = new $actionClassName($app);
        $action->run();
    }
}
