<?php

require __DIR__.'/../app/bootstrap.php';

$request    = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

/** @var \Middlewares\Utils\Dispatcher $dispatcher */
$dispatcher = $container->get('dispatcher');

$response = $dispatcher->dispatch($request);

$emitter = new \Zend\Diactoros\Response\SapiEmitter();

$emitter->emit($response);
