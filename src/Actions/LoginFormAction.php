<?php

namespace CommentApp\Actions;

/**
 * Render login form
 */
class LoginFormAction extends AbstractAction {
    
    public function run()
    {
        $username = $this->getApp()->getRequest()->getParam('username');
        
        $this->getApp()
            ->getResponse()
            ->setView('login.php', ['username' => $username]);
    }
}
