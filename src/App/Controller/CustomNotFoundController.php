<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class CustomNotFoundController
{
    /**
     * @Inject
     * @var \Twig_Environment
     */
    private $twig;

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->twig->render('custom404.html.twig'), 404);
    }
}
