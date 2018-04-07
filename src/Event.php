<?php

namespace CommentApp;

/**
 * Keeps all necessary info for triggered event
 * 
 * @property Application $app
 * @property string      $name of event
 * @property object      $subject
 */
class Event {
    private $app;
    private $name;
    private $subject;
    
    /**
     * 
     * @param object $subject which for event was created
     * @param Application $app
     * @param string $name of the event
     */
    public function __construct($subject, Application $app, string $name) {
        $this->subject = $subject;
        $this->app     = $app;
        $this->name    = $name;
    }
    
    /**
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }
    
    /**
     * 
     * @return object
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
