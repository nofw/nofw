<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Whoops;

use Whoops\Handler\Handler;

class ProductionHandler extends Handler
{
    /**
     * @var string
     */
    private $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public function handle(): int
    {
        if ('prod' === $this->env) {
            echo '<h1>It doesnt work!</h1>';

            return Handler::QUIT;
        }

        return Handler::DONE;
    }
}
