<?php

return [
    'view_paths' => [
        APP_ROOT.'/app/templates/',
    ],
    'locale' => \DI\env('APP_LOCALE', 'en_US.UTF-8'),
    'middlewares' => [
        \DI\get(\Nofw\Foundation\Http\Middleware\ErrorPageContent::class),
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Nofw\Foundation\Http\Middleware\HttpException::class),
        \DI\get(\Middlewares\PhpSession::class),
        \DI\get(\Middlewares\FastRoute::class),
        \DI\get(\Middlewares\RequestHandler::class), // Last middleware in the chain
    ],
    'dispatcher' => \DI\object(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
    \Interop\Http\Factory\StreamFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\StreamFactory::class),
    \Interop\Http\Factory\ResponseFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\ResponseFactory::class),
    \Middlewares\RequestHandler::class => \DI\object()->constructor(\DI\get(\Middlewares\Utils\CallableResolver\ContainerResolver::class)),
    \Middlewares\Whoops::class => \DI\object()->constructor(\DI\get(\Whoops\RunInterface::class)),
    \FastRoute\Dispatcher::class => \DI\factory('FastRoute\\cachedDispatcher')
        ->parameter(
            'routeDefinitionCallback',
            function (\FastRoute\RouteCollector $r) {
                $routeList = require APP_ROOT.'/etc/routes.php';

                foreach ($routeList as $routeDef) {
                    $r->addRoute($routeDef[0], $routeDef[1], $routeDef[2]);
                }
            }
        )
        ->parameter(
            'options',
            \DI\factory(function (bool $debug) {
                return [
                    'cacheDisabled' => $debug,
                    'cacheFile' => APP_ROOT.'/var/cache/router.php',
                ];
            })->parameter('debug', \DI\get('debug'))
        ),
    \Twig_Environment::class => \DI\factory(function ($debug, $viewPaths) {
        $twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem($viewPaths, APP_ROOT),
            [
                'debug' => $debug,
                'cache' => $debug ? false : APP_ROOT.'/var/cache/twig/',
            ]
        );

        $twig->addExtension(new \Twig_Extensions_Extension_I18n());

        return $twig;
    })
        ->parameter('debug', \DI\get('debug'))
        ->parameter('viewPaths', \DI\get('view_paths')),
    \Whoops\RunInterface::class => \DI\object(\Whoops\Run::class)
        ->method(
            'pushHandler',
            \DI\object(\Nofw\Emperror\Integration\Whoops\Handler::class)
                ->constructor(\DI\get(\Nofw\Error\ErrorHandler::class))
        ),
    \Nofw\Error\ErrorHandler::class => \DI\object(\Nofw\Emperror\ErrorHandler::class)
        ->method(
            'pushHandler',
            \DI\object(\Nofw\Error\Psr3ErrorHandler::class)
                ->constructor(\DI\get(\Psr\Log\LoggerInterface::class))
        ),
    \Psr\Log\LoggerInterface::class => function (\Interop\Container\ContainerInterface $container) {
        $monolog = new \Monolog\Logger('nofw');

        $monolog->pushHandler(new \Monolog\Handler\StreamHandler(APP_ROOT.'/var/log/'.$container->get('env').'.log'));

        return $monolog;
    },
];
