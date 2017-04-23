<?php

define('APP_ROOT', realpath(__DIR__.'/../'));

require APP_ROOT.'/vendor/autoload.php';

$env = getenv('APP_ENV') ?: 'prod';

// Container setup
$containerBuilder = (new \DI\ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->addDefinitions(APP_ROOT.'/etc/container.php')
;

require APP_ROOT.'/etc/env/'.$env.'.php';

$container = $containerBuilder->build();

// Locale setup
$locale = $container->get('locale');
$domain = 'messages';

putenv('LANGUAGE='.$locale);
setlocale(LC_ALL, $locale);

bindtextdomain($domain, APP_ROOT.'/app/locale/');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

// Session setup
if ($container->has(\SessionHandlerInterface::class)) {
    session_set_save_handler($container->get(\SessionHandlerInterface::class), true);
}
