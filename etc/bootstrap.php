<?php

require __DIR__.'/../vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'prod';
define('APP_ROOT', realpath(__DIR__.'/../'));

// Container setup
$containerBuilder = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__.'/container.php')
;

require __DIR__.'/env/'.$env.'.php';

if (file_exists(__DIR__.'/env/local.php')) {
    require __DIR__.'/env/local.php';
}

$container = $containerBuilder->build();

// Locale setup
$locale = $container->get('locale');
$domain = 'messages';

putenv("LANGUAGE=" . $locale);
setlocale(LC_ALL, $locale);

bindtextdomain($domain, APP_ROOT.'/locale/');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

// Session setup
if ($container->has(\SessionHandlerInterface::class)) {
    session_set_save_handler($container->get(\SessionHandlerInterface::class), true);
}
