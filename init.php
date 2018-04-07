<?php
/**
 * Inits settings all available observers to db table
 */
require_once __DIR__ . '/vendor/autoload.php';

$config = new \CommentApp\Config(realpath(__DIR__));
$connection = new CommentApp\Connection($config);

$commandsString = file_get_contents(__DIR__ . '/db-init-commands.sql');
if (false === $connection->query($commandsString)) {
    $errorInfo = $connection->errorInfo();
    echo "Error on db command:" . end($errorInfo), "\n";
}

$observerList = require __DIR__ . '/config/observer_reference.php';
$observerKeyList = array_keys($observerList);
$observerRepo = new \CommentApp\Repositories\ObserverRepository($connection);
$observerRepo->resetList($observerKeyList);