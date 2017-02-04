<?php

return [
    'env' => \DI\env('APPLICATION_ENV', 'prod'),
    'middlewares' => [
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Middlewares\FastRoute::class),
    ],
    \Middlewares\FastRoute::class => \DI\object()
        ->methodParameter('resolver', 'resolver', \DI\get(\DI\Container::class))
    ,
    \Whoops\Run::class => function(\Interop\Container\ContainerInterface $container) {
        $whoops = new \Whoops\Run();

        $whoops
            ->pushHandler($container->get(\Whoops\Handler\PrettyPageHandler::class))
            ->pushHandler($container->get(\Nofw\Infrastructure\Whoops\ProductionHandler::class))
            // TODO: add error/log handler
        ;

        return $whoops;
    },
    \Middlewares\Whoops::class => \DI\object()->constructor(\DI\get(\Whoops\Run::class)),
    \Nofw\Infrastructure\Whoops\ProductionHandler::class => \DI\object()->constructor(\DI\get('env')),
    'dispatcher' => \DI\object(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
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
