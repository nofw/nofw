<?php

return [
    'view_paths' => [
        APP_ROOT.'/app/templates/',
    ],
    'locale' => \DI\env('APP_LOCALE', 'en_US.UTF-8'),
    'middlewares' => [
        \DI\autowire(\Nofw\Foundation\Http\Middleware\ErrorPageContent::class),
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Nofw\Foundation\Http\Middleware\HttpException::class),
        \DI\get(\Middlewares\PhpSession::class),
        \DI\get(\Middlewares\FastRoute::class),
        \DI\get(\Middlewares\RequestHandler::class), // Last middleware in the chain
    ],
    'dispatcher' => \DI\create(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
    \Interop\Http\Factory\StreamFactoryInterface::class => \DI\create(\Middlewares\Utils\Factory\StreamFactory::class),
    \Interop\Http\Factory\ResponseFactoryInterface::class => \DI\create(\Middlewares\Utils\Factory\ResponseFactory::class),
    \Middlewares\Utils\CallableResolver\CallableResolverInterface::class => \DI\autowire(\Middlewares\Utils\CallableResolver\ContainerResolver::class),
    \Nofw\Foundation\Http\Middleware\ErrorPageContent::class => \DI\autowire(),
    \Middlewares\Whoops::class => \DI\create()->constructor(\DI\get(\Whoops\RunInterface::class)),
    \Middlewares\FastRoute::class => \DI\autowire(),
    \Middlewares\RequestHandler::class => \DI\create()->constructor(\DI\get(\Middlewares\Utils\CallableResolver\CallableResolverInterface::class)),
    \FastRoute\Dispatcher::class => \DI\factory('FastRoute\\cachedDispatcher')
        ->parameter(
            'routeDefinitionCallback',
            \DI\value(function (\FastRoute\RouteCollector $r) {
                $routeList = require APP_ROOT.'/etc/routes.php';

                foreach ($routeList as $routeDef) {
                    $r->addRoute($routeDef[0], $routeDef[1], $routeDef[2]);
                }
            })
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
    \Twig_LoaderInterface::class => \DI\create(\Twig_Loader_Filesystem::class)
        ->constructor(\DI\get('view_paths'), \DI\value(APP_ROOT)),
    'twig_options' => \DI\factory(function ($debug) {
        return             [
            'debug' => $debug,
            'cache' => $debug ? false : APP_ROOT.'/var/cache/twig/',
        ];
    })
        ->parameter('debug', \DI\get('debug')),
    \Twig_Environment::class => \DI\create()
        ->constructor(\DI\get(\Twig_LoaderInterface::class), \DI\get('twig_options'))
        ->method('addExtension', \DI\create(\Twig_Extensions_Extension_I18n::class)),
    \Whoops\RunInterface::class => \DI\create(\Whoops\Run::class)
        ->method(
            'pushHandler',
            \DI\create(\Nofw\Emperror\Integration\Whoops\Handler::class)
                ->constructor(\DI\get(\Nofw\Error\ErrorHandler::class))
        ),
    \Nofw\Error\ErrorHandler::class => \DI\create(\Nofw\Emperror\ErrorHandler::class)
        ->method(
            'pushHandler',
            \DI\create(\Nofw\Error\Psr3ErrorHandler::class)
                ->constructor(\DI\get(\Psr\Log\LoggerInterface::class))
        ),
    'monolog_handlers' => [
        \DI\create(\Monolog\Handler\StreamHandler::class)
            ->constructor(\DI\string(APP_ROOT.'/var/log/{env}.log')),
    ],
    'monolog_processors' => [],
    \Psr\Log\LoggerInterface::class => \DI\create(\Monolog\Logger::class)
        ->constructor('nofw', \DI\get('monolog_handlers'), \DI\get('monolog_processors')),
];
