<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ServerRequestInterface;

final class ErrorController
{
    public function __invoke(ServerRequestInterface $request)
    {
        throw new \Exception('Hello world!');
    }
}
