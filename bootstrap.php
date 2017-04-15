<?php

require __DIR__.'/vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'prod';
define('APP_ROOT', __DIR__);

$containerBuilder = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__.'/etc/container.php')
    //->addDefinitions(__DIR__.'/etc/container.extras.php') // Uncomment to use advanced features
;

require __DIR__.'/etc/env/'.$env.'.php';

if (file_exists(__DIR__.'/etc/env/local.php')) {
    require __DIR__.'/etc/env/local.php';
}

$container = $containerBuilder->build();
