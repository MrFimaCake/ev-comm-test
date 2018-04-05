<?php

namespace CommentApp;

use ReflectionClass;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventManager
 *
 * @author mrcake
 */
class EventManager {
    //put your code here
    private $app;
    private $observers = [];
    private $observerReference = [];
    
    private static $selfInstance;
    
    private function __construct(Application $app = null) {
        $this->app = $app;
    }
    
    public static function initEventManager(Application $app)
    {
        self::$selfInstance = new self($app);
        
        $config = $app->getConfig();
        
        $dbObservers = $app->getRepo('observer')->getAll();
        $observerReference = $config->getObserverReference();
        
        foreach ($config->getObservers() as $observer) {
            $key = $observer['key'];
            if (isset($observerReference[$key])) {
                $reference = $observerReference[$key];
                $subjects = (array)$reference['subject'];
                foreach ($subjects as $subject) {
                    self::attachObserver($subject, $reference['class']);
                }
            }
        }
    }

    public static function getInstance() : self
    {
        return self::$selfInstance;
    }
    
    public static function triggerEvent($name, $subject)
    {
        return self::getInstance()->internalTriggerEvent($name, $subject);
    }
    
    public static function attachObserver($subject, $observer, $priority = PHP_INT_MAX)
    {
        return self::getInstance()->internalAttachObserver($subject, $observer, $priority);
    }
    
    public function internalAttachObserver($subject, $observer, $priority = PHP_INT_MAX)
    {
        $className = is_object($subject) ? get_class($subject) : $subject;
        
        if (class_exists($className)) {
            $subjectClassName = $className;
        } elseif (isset($this->observerReference[$className])) {
            $subjectClassName = $this->observerReference[$className];
        }
        
        if (!isset($this->observers[$subjectClassName])) {
            $this->observers[$subjectClassName] = [];
        }
        
        $this->observers[$subjectClassName][] = $observer;
    }

    public function internalTriggerEvent($name, $subject) {
        $event = new Event($subject, $this->app, $name);
        
        $subjectClassName = get_class($subject);
        $observers = $this->observers[$subjectClassName] ?? [];
        
        foreach ($observers as $observer) {
            if (is_callable($observer)) {
                $result = call_user_func($observer, $event);
            } elseif (is_object($observer) && method_exists($observer, $name)) {
                $result = $observer->{$name}($event);
            } elseif (is_string($observer) 
                && class_exists($observer)
                && method_exists($created = new $observer, $name)) {
                $result = $created->{$name}($event);
            } else {
                $result = null;
            }
            
            if ($result === false) {
                break;
            }
        }
        
        return $event;
    }
}
