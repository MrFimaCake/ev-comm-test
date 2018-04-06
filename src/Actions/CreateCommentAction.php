<?php

namespace CommentApp\Actions;


use CommentApp\Models\Comment;
use CommentApp\Models\CommentSource;

/**
 * Creating comment
 */
class CreateCommentAction extends AbstractAction{

    public function run()
    {
        /** @var \CommentApp\Request $request */
        /** @var \CommentApp\Repositories\CommentRepository $repo */
        /** @var \CommentApp\Repositories\CommentSourceRepository $sourceRepo */
        /** @var \CommentApp\Models\User $user */
        $request = $this->getApp()->getRequest();
        $repo = $this->getApp()->getRepo(Comment::class);
        $sourceRepo = $this->getApp()->getRepo(CommentSource::class);
        $user = $request->getUser();
        
        $commentSourceHash = $request->getParam('comment_source_hash');
        $commentSource = $sourceRepo->findOrCreateHash($commentSourceHash);
        
        $commentBody = $request->getParam('comment_body');
        
        if ($commentBody) {
            $repo->create($request->getParam('comment_body'), $user, $commentSource);
        }
        
        $this->getApp()->getResponse()->addRedirect('/');
    }
}
