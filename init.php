<?php

require_once __DIR__ . '/vendor/autoload.php';

//$dbCommands = require_once __DIR__ . '/db-init-commands.php';

$config = new \CommentApp\Config(realpath(__DIR__));
$connection = new CommentApp\Connection($config);

$commandsString = file_get_contents(__DIR__ . '/db-init-commands.sql');

if (false === $connection->query($commandsString)) {
    $errorInfo = $connection->errorInfo();
    echo "Error on db command:" . end($errorInfo), "\n";
}

$observerList = require __DIR__ . '/config/default_observers.php';
$observerRepo = new \CommentApp\Repositories\ObserverRepository($connection);
$observerRepo->resetList($observerList);