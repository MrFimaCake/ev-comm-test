<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Actions;

/**
 * Description of LoginFormAction
 *
 * @author mrcake
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
