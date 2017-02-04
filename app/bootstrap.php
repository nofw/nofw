<?php

require __DIR__.'/../vendor/autoload.php';

define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'prod');

$containerBuilder = (new \DI\ContainerBuilder())
    //->useAnnotations(true) // TODO: composer require doctrine/annotations
    ->useAutowiring(true)
    ->addDefinitions(__DIR__.'/config.php')
;

require __DIR__.'/env/'.APPLICATION_ENV.'.php';

$container = $containerBuilder->build();
