<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ErrorController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        throw new \Exception('Hello world!');
    }
}
