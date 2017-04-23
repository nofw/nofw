<?php

return [
    \Whoops\RunInterface::class => \DI\decorate(function ($whoops, \Interop\Container\ContainerInterface $container) {
        /** @var \Whoops\RunInterface $whoops */
        $handlers = $whoops->getHandlers();

        $whoops->clearHandlers();

        $whoops->pushHandler($container->get(\Whoops\Handler\PrettyPageHandler::class));

        /** @var \Whoops\Handler\HandlerInterface $handler */
        foreach ($handlers as $handler) {
            $whoops->pushHandler($handler);
        }

        return $whoops;
    }),
    \Whoops\Handler\PrettyPageHandler::class => function (\Interop\Container\ContainerInterface $container) {
        $prettyPage = new \Whoops\Handler\PrettyPageHandler();

        // Blacklist environment variables
        if ($container->has('whoops_blacklist')) {
            foreach ($container->get('whoops_blacklist') as $superGlobal => $values) {
                foreach ($values as $value) {
                    $prettyPage->blacklist($superGlobal, $value);
                }
            }
        }

        return $prettyPage;
    },
    \Psr\Log\LoggerInterface::class => \DI\decorate(function ($monolog) {
        /** @var \Monolog\Logger $monolog */
        $monolog->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());

        return $monolog;
    }),
];
