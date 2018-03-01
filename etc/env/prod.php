<?php

/* @var \DI\ContainerBuilder $containerBuilder */
$containerBuilder
    ->writeProxiesToFile(true, APP_ROOT.'/var/cache/container/proxies/')
    ->enableCompilation(APP_ROOT.'/var/cache/container')
;
