<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Observers;

use CommentApp\Event;
use CommentApp\Models\User;
/**
 * Description of RequestObserver
 *
 * @author mrcake
 */
class RequestObserver {
    //put your code here
    
    public function onRequestInit(Event $event)
    {
        //create user
        /** @var \CommentApp\Request $request */
        $request = $event->getSubject();
        $userRepo = $event->getApp()->getRepo(User::class);
        
        if ($request->hasUserCookieHash()
            && $user = $userRepo->getUserByHash($request->getUserCookieHash())) {
            $request->setUser($user);
        } elseif ($user = $request->createUserFromRequest($event->getApp()->getRepo(User::class))) {
            
            $response = $event->getApp()->getResponse();
            $response->addRedirect('/');
            $response->send();
        } else {
            $request->initGuestUser($request->getUserCookieHash());
        }
    }
    
}
