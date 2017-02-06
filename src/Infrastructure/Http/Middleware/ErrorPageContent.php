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
        if ($this->isError($response) && $response->getBody()->getSize() < 1) {
            if ($this->isHtml($request, $response)) {
                switch ($response->getStatusCode()) {
                    case 404:
                        $html = $this->twig->render('error/error404.html.twig');
                        break;

                    default:
                        $html = $this->twig->render('error/error.html.twig', [
                            'status_code' => $response->getStatusCode(),
                            'reason_phrase' => $response->getReasonPhrase(),
                        ]);
                        break;
                }

                $body = new Stream('php://temp', 'wb+');
                $body->write($html);
                $body->rewind();

                return $response->withBody($body);
            }
        }

        return $response;
    }

    private function isError(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 400 && $response->getStatusCode() < 600;
    }

    private function isHtml(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        $accept = $request->getHeaderLine('Accept');
        $contentType = $response->getHeaderLine('Content-Type');

        // TODO: improve negotiation
        return empty($accept) ||
            stripos($accept, '*') !== false ||
            stripos($accept, 'text/html') !== false ||
            stripos($contentType, 'text/html') !== false
        ;
    }
}
