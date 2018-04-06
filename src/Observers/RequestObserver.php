<?php

namespace CommentApp\Observers;

use CommentApp\Event;
use CommentApp\Models\User;

class RequestObserver {
    //put your code here
    
    /**
     * Called on request initiation
     * 
     * @param Event $event
     */
    public function onRequestInit(Event $event)
    {
        /** @var \CommentApp\Request $request */
        /** @var \CommentApp\Repositories\UserRepository $repo */
        /** @var \CommentApp\Response $response */
        $request = $event->getSubject();
        $userRepo = $event->getApp()->getRepo(User::class);
        
        if ($request->hasUserCookieHash()
            && $user = $userRepo->getUserByHash($request->getUserCookieHash())) {
            $request->setUser($user);
        } elseif ($request->createUserFromRequest($userRepo)) {
            $response = $event->getApp()->getResponse();
            $response->addRedirect('/');
            $response->send();
        } else {
            $request->initGuestUser($request->getUserCookieHash());
        }
    }
}
