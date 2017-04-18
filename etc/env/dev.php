<?php

if (file_exists(__DIR__.'/../container.local.php')) {
    /* @var \DI\ContainerBuilder $containerBuilder */
    $containerBuilder->addDefinitions(__DIR__.'/../container.local.php');
}
