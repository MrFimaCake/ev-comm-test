<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Actions;

/**
 * Description of LogoutAction
 *
 * @author mrcake
 */
class LogoutAction extends AbstractAction{
    //put your code here
    
    public function run()
    {
        $this->getApp()->getRequest()->cleanUserCookieHash();
        $response = $this->getApp()->getResponse();
        $response->addRedirect('/');
        $response->send();
    }
    
}
