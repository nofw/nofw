<?php

return [
    'env' => \DI\env('APPLICATION_ENV', 'prod'),
    'debug' => \DI\env(
        'APPLICATION_DEBUG',
        \DI\factory(
            function(string $env) {
                return 'dev' === $env;
            }
        )->parameter('env', \DI\get('env'))
    ),
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
    \Nofw\Infrastructure\Whoops\ProductionHandler::class => \DI\object()->constructorParameter('debug', \DI\get('debug')),
    'dispatcher' => \DI\object(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
    \FastRoute\Dispatcher::class => \DI\factory('FastRoute\\cachedDispatcher')
        ->parameter(
            'routeDefinitionCallback',
            function(\FastRoute\RouteCollector $r) {
                $routeList = require APP_ROOT.'/routes.php';

                foreach ($routeList as $routeDef) {
                    $r->addRoute($routeDef[0], $routeDef[1], $routeDef[2]);
                }
            }
        )
        ->parameter(
            'options',
            \DI\factory(function(bool $debug) {
                return [
                    'cacheDisabled' => $debug,
                    'cacheFile' => APP_ROOT.'/../var/cache/router.php',
                ];
            })->parameter('debug', \DI\get('debug'))
        )
    ,
   \Twig_Environment::class => \DI\object()->constructor(
       \DI\object(\Twig_Loader_Filesystem::class)->constructor([APP_ROOT.'/../src/App/View/']),
       \DI\factory(function($debug) {
           return [
               'debug' => $debug,
               'cache' => $debug ? false : APP_ROOT.'/../var/cache/twig/',
           ];
       })->parameter('debug', \DI\get('debug'))
   ),
];
