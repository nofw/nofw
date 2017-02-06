<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Whoops;

use Whoops\Handler\Handler;

class ProductionHandler extends Handler
{
    /**
     * @var bool
     */
    private $debug = false;

    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function handle(): int
    {
        if ($this->debug) {
            return Handler::DONE;
        }

        return Handler::QUIT;
    }
}
