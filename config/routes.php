<?php

return [
    [
        'url'    => '/',
        'method' => 'GET',
        'params' => [],
        'action' => CommentApp\Actions\CommentListAction::class,
        'authOnly' => true,
    ],
    [
        'url'    => '/',
        'method' => 'GET',
        'action' => CommentApp\Actions\LoginFormAction::class,
    ],
    [
        'url'      => '/',
        'method'   => 'POST',
        'authOnly' => true,
        'action'   => CommentApp\Actions\CreateCommentAction::class,
    ],
    [
        'url'      => '/logout',
        'method'   => 'POST',
        'authOnly' => true,
        'action'   => CommentApp\Actions\LogoutAction::class,
    ]
];