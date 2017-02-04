<?php

if (file_exists(__DIR__.'/../config.local.php')) {
    /** @var \DI\ContainerBuilder $containerBuilder */
    $containerBuilder->addDefinitions(__DIR__.'/../config.local.php');
}
