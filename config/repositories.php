<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    CommentApp\Models\User::class          => CommentApp\Repositories\UserRepository::class,
    'observer'                             => CommentApp\Repositories\ObserverRepository::class,
    CommentApp\Models\Comment::class       => CommentApp\Repositories\CommentRepository::class,
    CommentApp\Models\CommentSource::class => CommentApp\Repositories\CommentSourceRepository::class,
];
