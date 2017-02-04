<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class HomeController
{
    /**
     * @Inject
     * @var \Twig_Environment
     */
    private $twig;

    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse($this->twig->render('home.html.twig'));
    }
}
