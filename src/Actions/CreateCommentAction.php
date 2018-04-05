<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp\Actions;


use CommentApp\Models\Comment;
use CommentApp\Models\CommentSource;

class CreateCommentAction extends AbstractAction{

    public function run()
    {
        $request = $this->getApp()->getRequest();
        $repo = $this->getApp()->getRepo(Comment::class);
        $sourceRepo = $this->getApp()->getRepo(CommentSource::class);
        $user = $request->getUser();
        
        $commentSourceHash = $request->getParam('comment_source_hash');
        $commentSource = $sourceRepo->findOrCreateHash($commentSourceHash);
        
        $commentBody = $request->getParam('comment_body');
        
        if ($commentBody) {
            $repo->save($request->getParam('comment_body'), $user, $commentSource);
        }
        
        $this->getApp()->getResponse()->addRedirect('/');
    }
}
