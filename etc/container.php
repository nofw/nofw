<?php

return [
    'env' => \DI\env('APP_ENV', 'prod'),
    'debug' => \DI\factory(function (string $env, ?string $debug): bool {
        if (null !== $debug) {
            return 'true' === $debug ? true : false;
        }

        return 'dev' === $env;
    })
        ->parameter('env', \DI\get('env'))
        ->parameter('debug', \DI\env('APP_DEBUG', null))
    ,
    'view_paths' => [
        APP_ROOT.'/app/templates/',
    ],
    'locale' => \DI\env('APP_LOCALE', 'en_US.UTF-8'),
    'middlewares' => [
        \DI\get(\Nofw\Foundation\Http\Middleware\ErrorPageContent::class),
        \DI\get(\Middlewares\Whoops::class),
        \DI\get(\Nofw\Foundation\Http\Middleware\HttpException::class),
        \DI\get(\Middlewares\PhpSession::class),
        \DI\get(\Middlewares\FastRoute::class), // Last middleware in the chain
    ],
    'dispatcher' => \DI\object(\Middlewares\Utils\Dispatcher::class)->constructor(\DI\get('middlewares')),
    \Interop\Http\Factory\StreamFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\StreamFactory::class),
    \Interop\Http\Factory\ResponseFactoryInterface::class => \DI\get(\Middlewares\Utils\Factory\ResponseFactory::class),
    \Middlewares\FastRoute::class => \DI\object()->methodParameter(
        'container',
        'container',
        \DI\get(\DI\Container::class)
    ),
    \Middlewares\Whoops::class => \DI\object()->constructor(\DI\get(\Whoops\RunInterface::class)),
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
    \Twig_Environment::class => \DI\factory(function($debug, $viewPaths) {
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
        ->parameter('viewPaths', \DI\get('view_paths'))
    ,
    \Whoops\RunInterface::class => function(\Interop\Container\ContainerInterface $container) {
        $whoops = new \Whoops\Run();

        $prettyPage = new \Whoops\Handler\PrettyPageHandler();

        // Blacklist environment variables
        if ($container->has('whoops_blacklist')) {
            foreach ($container->get('whoops_blacklist') as $superGlobal => $values) {
                foreach ($values as $value) {
                    $prettyPage->blacklist($superGlobal, $value);
                }
            }
        }

        $whoops
            ->pushHandler($prettyPage)
            ->pushHandler(new \SKM\Whoops\Handler\ProductionHandler($container->get('debug')))
            ->pushHandler($container->get(\SKM\Whoops\Handler\LogHandler::class))
        ;

        return $whoops;
    },
    \Psr\Log\LoggerInterface::class => function (\Interop\Container\ContainerInterface $container) {
        $monolog = new \Monolog\Logger('nofw');

        $monolog->pushHandler(new \Monolog\Handler\StreamHandler(APP_ROOT . '/var/log/' . $container->get('env') . '.log'));

        if ($container->get('debug')) {
            $monolog->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
        }

        return $monolog;
    },
];
