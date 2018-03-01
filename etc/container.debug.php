<?php

return [
    \Whoops\RunInterface::class => \DI\decorate(function (\Whoops\RunInterface $whoops, \Psr\Container\ContainerInterface $container) {
        $handlers = $whoops->getHandlers();

        $whoops->clearHandlers();

        $whoops->pushHandler($container->get(\Whoops\Handler\PrettyPageHandler::class));

        foreach ($handlers as $handler) {
            $whoops->pushHandler($handler);
        }

        return $whoops;
    }),
    \Whoops\Handler\PrettyPageHandler::class => function (\Psr\Container\ContainerInterface $container) {
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
    'monolog_handlers' => \DI\add([
        \DI\create(\Monolog\Handler\BrowserConsoleHandler::class),
    ]),
];
