<?php

return [
    \Whoops\Run::class => \DI\decorate(function ($whoops, \Interop\Container\ContainerInterface $container) {
        $whoops->pushHandler($container->get(\Nofw\Infrastructure\Whoops\AirbrakeHandler::class));

        return $whoops;
    }),
    \Airbrake\Notifier::class => \DI\object()->constructor(
        \DI\factory(function($host, $projectId, $projectKey, $env) {
            return [
                'host' => $host,
                'projectId' => $projectId,
                'projectKey' => $projectKey,
                'environment' => $env,
            ];
        })
            ->parameter('host', \DI\env('AIRBRAKE_HOST'))
            ->parameter('projectId', \DI\env('AIRBRAKE_PROJECT_ID'))
            ->parameter('projectKey', \DI\env('AIRBRAKE_PROJECT_KEY'))
            ->parameter('env', \DI\get('env'))
    ),
];
