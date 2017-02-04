<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

final class NotFoundController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new Response('php://memory', 404);
    }
}
