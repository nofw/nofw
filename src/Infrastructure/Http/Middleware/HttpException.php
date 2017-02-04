<?php

namespace Nofw\Infrastructure\Http\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Nofw\Infrastructure\Http\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

final class HttpException implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        try {
            $response = $delegate->process($request);
        } catch (Exception $e) {
            return new Response('php://memory', $e->getStatusCode());
        }

        return $response;
    }
}
