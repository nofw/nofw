<?php

/* @var \DI\ContainerBuilder $containerBuilder */
$containerBuilder
    ->writeProxiesToFile(true, APP_ROOT.'/var/cache/container/proxies/')
    ->compile(APP_ROOT.'/var/cache/container/CompiledContainer.php')
;
