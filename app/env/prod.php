<?php

$cache = new \Doctrine\Common\Cache\ApcuCache();
$cache->setNamespace('container');

/** @var \DI\ContainerBuilder $containerBuilder */
$containerBuilder
    ->writeProxiesToFile(true, __DIR__.'/../../var/cache/proxies/')
    ->setDefinitionCache($cache)
;
