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
    'datetime' => [
        'class'   => CommentApp\Observers\DateTimeObserver::class,
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
