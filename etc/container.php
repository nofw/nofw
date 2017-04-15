<?php

return [
    'env' => \DI\env('APP_ENV', 'prod'),
    'debug' => \DI\env(
        'APP_DEBUG',
        \DI\factory(
            function(string $env) {
                return 'dev' === $env;
            }
        )->parameter('env', \DI\get('env'))
    ),
    'view_paths' => [
        APP_ROOT.'/templates/',
    ],
    'locale' => \DI\env('APP_LOCALE', 'en_US.UTF-8'),
    'middlewares' => [
        \DI\get(\Nofw\Foundation\Http\Middleware\ErrorPageContent::class),
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Nofw\Foundation\Http\Middleware\HttpException::class),
        \DI\get(\Middlewares\FastRoute::class),
    ],
    \Interop\Http\Factory\StreamFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\StreamFactory::class),
    \Interop\Http\Factory\ResponseFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\ResponseFactory::class),
    \Middlewares\FastRoute::class => \DI\object()
        ->methodParameter('container', 'container', \DI\get(\DI\Container::class))
    ,
    \Middlewares\Whoops::class => \DI\object()->constructor(\DI\get(\Whoops\Run::class)),
    'dispatcher' => \DI\object(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
    \FastRoute\Dispatcher::class => \DI\factory('FastRoute\\cachedDispatcher')
        ->parameter(
            'routeDefinitionCallback',
            function(\FastRoute\RouteCollector $r) {
                $routeList = require APP_ROOT.'/etc/routes.php';

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
                    'cacheFile' => APP_ROOT.'/var/cache/router.php',
                ];
            })->parameter('debug', \DI\get('debug'))
        )
    ,
    \Twig_LoaderInterface::class => \DI\object(\Twig_Loader_Filesystem::class)->constructor(\DI\get('view_paths')),
    \Twig_Environment::class => function(\Interop\Container\ContainerInterface $container) {
        $debug = $container->get('debug');
        $twig = new \Twig_Environment(
            $container->get(\Twig_LoaderInterface::class),
            [
                'debug' => $debug,
                'cache' => $debug ? false : APP_ROOT.'/var/cache/twig/',
            ]
        );

        $twig->addExtension($container->get(\Twig_Extensions_Extension_I18n::class));

        return $twig;
    },
    \Whoops\Run::class => function(\Interop\Container\ContainerInterface $container) {
        $whoops = new \Whoops\Run();

        $whoops
            ->pushHandler($container->get(\Whoops\Handler\PrettyPageHandler::class))
            ->pushHandler($container->get(\SKM\Whoops\Handler\ProductionHandler::class))
            ->pushHandler($container->get(\SKM\Whoops\Handler\LogHandler::class))
        ;

        return $whoops;
    },
    \SKM\Whoops\Handler\ProductionHandler::class => \DI\object()
        ->constructorParameter('debug', \DI\get('debug'))
    ,
    \Psr\Log\LoggerInterface::class => function (\Interop\Container\ContainerInterface $container) {
        $monolog = new \Monolog\Logger('nofw');

        $handler = new \Monolog\Handler\StreamHandler('php://stdout');

        $monolog->pushHandler($handler);

        return $monolog;
    },
];
