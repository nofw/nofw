<?php

return [
    ['GET', '/', \Nofw\App\Controller\HomeController::class],
    ['GET', '/error', \Nofw\App\Controller\ErrorController::class],
    ['GET', '/404', \Nofw\App\Controller\NotFoundController::class],
    ['GET', '/custom404', \Nofw\App\Controller\CustomNotFoundController::class],
    ['GET', '/404exception', \Nofw\App\Controller\CustomNotFoundController::class],
];
