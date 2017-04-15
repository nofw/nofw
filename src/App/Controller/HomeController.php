<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class HomeController
{
    /**
     * @Inject
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @Inject
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Home page loaded');

        return new HtmlResponse($this->twig->render('home.html.twig'));
    }
}
