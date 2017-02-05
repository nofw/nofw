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
    'view_paths' => [
        APP_ROOT.'/../src/App/View/',
    ],
    'middlewares' => [
        \DI\get(\Nofw\Infrastructure\Http\Middleware\ErrorPageContent::class),
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Nofw\Infrastructure\Http\Middleware\HttpException::class),
        \DI\get(\Middlewares\FastRoute::class),
    ],
    \Middlewares\FastRoute::class => \DI\object()
        ->methodParameter('resolver', 'container', \DI\get(\DI\Container::class))
    ,
    \Middlewares\Whoops::class => \DI\object()->constructor(\DI\get(\Whoops\Run::class)),
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
       \DI\object(\Twig_Loader_Filesystem::class)->constructor(\DI\get('view_paths')),
       \DI\factory(function($debug) {
           return [
               'debug' => $debug,
               'cache' => $debug ? false : APP_ROOT.'/../var/cache/twig/',
           ];
       })->parameter('debug', \DI\get('debug'))
   ),
    \Whoops\Run::class => function(\Interop\Container\ContainerInterface $container) {
        $whoops = new \Whoops\Run();

        $whoops
            ->pushHandler($container->get(\Whoops\Handler\PrettyPageHandler::class))
            ->pushHandler($container->get(\Nofw\Infrastructure\Whoops\ProductionHandler::class))
            ->pushHandler($container->get(\Nofw\Infrastructure\Whoops\LogHandler::class))
        ;

        return $whoops;
    },
    \Nofw\Infrastructure\Whoops\ProductionHandler::class => \DI\object()
        ->constructorParameter('debug', \DI\get('debug'))
    ,
    \Psr\Log\LoggerInterface::class => function (\Interop\Container\ContainerInterface $container) {
        $monolog = new \Monolog\Logger('nofw');

        $handler = new \Monolog\Handler\StreamHandler('php://stdout');

        $monolog->pushHandler($handler);

        return $monolog;
    },
];
