<?php

namespace CommentApp\Actions;

/**
 * Clean user cookies and logout
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
