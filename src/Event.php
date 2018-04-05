<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

/**
 * Description of Event
 *
 * @author mrcake
 */
class Event {
    private $app;
    private $name;
    private $subject;
    //put your code here
    public function __construct($subject, $app, $name) {
        $this->subject = $subject;
        $this->app     = $app;
        $this->name    = $name;
    }
    
    public function getApp()
    {
        return $this->app;
    }
    
    public function getSubject()
    {
        return $this->subject;
    }
}
