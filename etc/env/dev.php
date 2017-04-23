<?php

// Load environment
try {
    $dotenv = new Dotenv\Dotenv(APP_ROOT);
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // Do nothing for now (.env is optional)
}

if (file_exists(__DIR__.'/../container.local.php')) {
    /* @var \DI\ContainerBuilder $containerBuilder */
    $containerBuilder->addDefinitions(__DIR__.'/../container.local.php');
}
