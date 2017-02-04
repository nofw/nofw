<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Http;

interface Exception
{
    public function getStatusCode(): int;
}
