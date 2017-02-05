<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Whoops;

use Airbrake\Notifier;
use Whoops\Handler\Handler;

final class AirbrakeHandler extends Handler
{
    /**
     * @var Notifier
     */
    private $notifier;

    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(): int
    {
        $this->notifier->notify($this->getException());

        return Handler::DONE;
    }
}
