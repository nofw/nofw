<?php

namespace Nofw\App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class HomeController
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse('<h1>It works!</h1>');
    }
}
