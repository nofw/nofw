<?php

namespace Nofw\App\Controller;

use Nofw\Foundation\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;

final class ErrorController
{
    /**
     * @Inject
     *
     * @var \Twig_Environment
     */
    private $twig;

    public function error(ServerRequestInterface $request): ResponseInterface
    {
        throw new \Exception('Hello world!');
    }

    public function notFound(ServerRequestInterface $request): ResponseInterface
    {
        return new Response('php://memory', 404);
    }

    public function customNotFound(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->twig->render('custom404.html.twig'), 404);
    }

    public function notFoundException(ServerRequestInterface $request): ResponseInterface
    {
        throw new NotFoundException();
    }
}
