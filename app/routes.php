<?php

return [
    ['GET', '/', \Nofw\App\Controller\HomeController::class],
    ['GET', '/error', [\Nofw\App\Controller\ErrorController::class, 'error']],
    ['GET', '/404', [\Nofw\App\Controller\ErrorController::class, 'notFound']],
    ['GET', '/custom404', [\Nofw\App\Controller\ErrorController::class, 'customNotFound']],
    ['GET', '/404exception', [\Nofw\App\Controller\ErrorController::class, 'notFoundException']],
];
