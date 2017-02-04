<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Whoops;

use Whoops\Handler\Handler;

class ProductionHandler extends Handler
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var bool
     */
    private $debug = false;

    public function __construct(\Twig_Environment $twig, bool $debug)
    {
        $this->twig = $twig;
        $this->debug = $debug;
    }

    public function handle(): int
    {
        if ($this->debug) {
            return Handler::DONE;
        }

        echo $this->twig->render('error/error500.html.twig');

        return Handler::QUIT;
    }
}
