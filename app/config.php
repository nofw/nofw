<?php

return [
    \FastRoute\Dispatcher::class => \DI\factory('FastRoute\\cachedDispatcher')
        ->parameter(
            'routeDefinitionCallback',
            function(\FastRoute\RouteCollector $r) {
                $routeList = require __DIR__.'/routes.php';

                foreach ($routeList as $routeDef) {
                    $r->addRoute($routeDef[0], $routeDef[1], $routeDef[2]);
                }
            }
        )
        ->parameter(
            'options',
            [
                'cacheDisabled' => APPLICATION_ENV == 'dev',
                'cacheFile' => __DIR__.'/../var/cache/router.php',
            ]
        )
    ,
];
