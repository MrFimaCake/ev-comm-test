<?php

return [
    'app_user' => [
        'class'   => CommentApp\Observers\ApplicationUserObserver::class,
        'subject' => CommentApp\Application::class,
    ],
    'request'  => [
        'class'   => CommentApp\Observers\RequestObserver::class,
        'subject' => CommentApp\Request::class,
    ],
//    'user'     => [
//        'class'   => CommentApp\Observers\UserObserver::class,
//        'subject' => CommentApp\Models\User::class,
//    ],
    'comment'  => [
        'class'   => CommentApp\Observers\CommentObserver::class,
        'subject' => CommentApp\Models\Comment::class, 
    ],
    'datetime' => [
        'class'   => CommentApp\Observers\CommentObserver::class,
        'subject' => [
            CommentApp\Models\User::class,
            CommentApp\Models\Comment::class,
            CommentApp\Models\CommentSource::class,
        ]
    ],
    'app'      => [
        'class' => CommentApp\Observers\ApplicationObserver::class,
        'subject' => CommentApp\Application::class,
    ],
];
