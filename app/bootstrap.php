<?php

require __DIR__.'/../vendor/autoload.php';

$env = getenv('APPLICATION_ENV') ?: 'prod';
define('APP_ROOT', __DIR__);

$containerBuilder = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__.'/config.php')
;

require __DIR__.'/env/'.$env.'.php';

$container = $containerBuilder->build();
