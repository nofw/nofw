<?php

require __DIR__.'/../vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'prod';
define('APP_ROOT', __DIR__);

$containerBuilder = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__.'/config.php')
    //->addDefinitions(__DIR__.'/config.extras.php') // Uncomment to use advanced features
;

require __DIR__.'/env/'.$env.'.php';

if (file_exists(__DIR__.'/env/local.php')) {
    require __DIR__.'/env/local.php';
}

$container = $containerBuilder->build();
