<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Http\Exception;

use Nofw\Infrastructure\Http\Exception;

final class NotFoundException extends \Exception implements Exception
{
    public function getStatusCode(): int
    {
        return 404;
    }
}
