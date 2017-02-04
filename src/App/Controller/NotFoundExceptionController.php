<?php

namespace Nofw\App\Controller;

use Nofw\Infrastructure\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NotFoundExceptionController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        throw new NotFoundException();
    }
}
