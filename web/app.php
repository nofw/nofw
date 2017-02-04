<?php

require __DIR__.'/../app/bootstrap.php';

$dispatcher = $container->get(\FastRoute\Dispatcher::class);

$route = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($route[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        echo 'not found';
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo 'method not allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        $container->call($controller, $parameters);
        break;
}
