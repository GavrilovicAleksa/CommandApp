<?php

include_once 'Request/RouterRequest.php';
include_once 'Router/Router.php';
include_once 'Controller/CommandController.php';

$router = new Router(new RouterRequest);
$commandController = new CommandController();

// Register calls

$router->post('/commands', function($request) use ($commandController) {
    return $commandController->generateCommandFileAction($request);
});

$router->get('/', function() {
    return <<<HTML
  <h1>Hello world</h1>
HTML;
});