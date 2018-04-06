<?php

namespace CommentApp;

use ReflectionClass;

/**
 * @property CommentApp\Application $app
 * @property array $observers list of keys for each observer
 * @property array $observerReference list of classes 
 */
class EventManager {
    
    private $app;
    private $observers = [];
    private $observerReference = [];
    
    private static $selfInstance;
    
    private function __construct(Application $app = null) {
        $this->app = $app;
    }
    
    /**
     * @param \CommentApp\Application $app
     */
    public static function initEventManager(Application $app)
    {
        self::$selfInstance = new self($app);
        
        $config = $app->getConfig();
        
        $dbObservers = $app->getRepo('observer')->getAll();
        $observerReference = $config->getObserverReference();
        
        foreach ($dbObservers as $dbObserver) {
            $key = $dbObserver['observer_key'];
            if (!isset($observerReference[$key])) {
                continue;
            }
            
            $reference = $observerReference[$key];
            $subjects = (array)$reference['subject'];
            foreach ($subjects as $subject) {
                self::attachObserver($subject, $reference['class']);
            }
        }
    }

    /**
     * @return self
     */
    public static function getInstance() : self
    {
        return self::$selfInstance;
    }
    
    /**
     * Triggers event from singleton
     * @see internalTriggerEvent()
     * 
     * @param string $name
     * @param type $subject
     * @return Event
     */
    public static function triggerEvent($name, $subject)
    {
        return self::getInstance()->internalTriggerEvent($name, $subject);
    }
    
    /**
     * Adds observer-subject pair to singleton
     * @param string $subject
     * @param string $observer
     * @return \CommentApp\Event
     */
    public static function attachObserver($subject, $observer)
    {
        return self::getInstance()->internalAttachObserver($subject, $observer);
    }
    
    /**
     * Adds observer-subject pair
     * @param type $subject
     * @param type $observer
     */
    public function internalAttachObserver($subject, $observer)
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

    /**
     * Gets each observer and calls given event
     * 
     * @param string $name of event
     * @param string $subject Class name of listened object
     * @return \CommentApp\Event
     */
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
