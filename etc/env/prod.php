<?php

$cache = new \Doctrine\Common\Cache\ChainCache([
    new \Doctrine\Common\Cache\ApcuCache(),
    new \Doctrine\Common\Cache\FilesystemCache(APP_ROOT . '/var/cache/container/definitions/'),
]);
$cache->setNamespace('container');

/** @var \DI\ContainerBuilder $containerBuilder */
$containerBuilder
    ->writeProxiesToFile(true, APP_ROOT . '/var/cache/container/proxies/')
    ->setDefinitionCache($cache)
;
