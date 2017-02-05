<?php

declare(strict_types=1);

namespace Nofw\Infrastructure\Whoops;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Whoops\Handler\Handler;

final class LogHandler extends Handler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(): int
    {
        $exception = $this->getException();
        $level = LogLevel::CRITICAL;

        if ($exception instanceof \ErrorException) {
            $level = $this->translateError($exception);
        }

        $this->logger->log($level, $exception->getMessage().$exception->getTraceAsString());

        return Handler::DONE;
    }

    private function translateError(\ErrorException $exception): string
    {
        switch ($exception->getSeverity()) {
            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                return LogLevel::ERROR;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                return LogLevel::WARNING;

            case E_NOTICE:
            case E_USER_NOTICE:
                return LogLevel::NOTICE;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return LogLevel::INFO;

            default:
                return LogLevel::CRITICAL;
        }
    }
}
