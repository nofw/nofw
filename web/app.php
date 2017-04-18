<?php

require __DIR__.'/../app/bootstrap.php';

$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Override debug parameter
$query = $request->getQueryParams();
if (isset($query['debug'])) {
    if ('false' === $query['debug']) {
        $container->set('debug', false);
    } elseif ('true' === $query['debug']) {
        $container->set('debug', true);
    }
}

/** @var \Middlewares\Utils\Dispatcher $dispatcher */
$dispatcher = $container->get('dispatcher');

$response = $dispatcher->dispatch($request);

$emitter = new \Zend\Diactoros\Response\SapiEmitter();

$emitter->emit($response);
