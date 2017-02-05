<?php

namespace Nofw\Infrastructure\Http\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;

final class ErrorPageContent implements MiddlewareInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $response = $delegate->process($request);

        // If there is an error, but there is no body
        if ($response->getStatusCode() >= 400 && $response->getBody()->getSize() < 1) {
            // TODO: negotiate content-type
            switch ($response->getStatusCode()) {
                case 404:
                    $html = $this->twig->render('error/error404.html.twig');
                    break;

                case 500:
                    $html = $this->twig->render('error/error500.html.twig');
                    break;

                default:
                    $html = $this->twig->render('error/error.html.twig');
                    break;
            }

            $body = new Stream('php://temp', 'wb+');
            $body->write($html);
            $body->rewind();

            return $response->withBody($body);
        }

        return $response;
    }
}
