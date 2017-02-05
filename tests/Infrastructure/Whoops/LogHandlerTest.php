<?php

namespace Tests\Nofw\Infrastructure\Whoops;

use Nofw\Infrastructure\Whoops\LogHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Whoops\Handler\Handler;
use Whoops\Handler\HandlerInterface;

final class LogHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_handler()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);
        $handler  = new LogHandler($logger->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * @test
     */
    public function it_logs_an_exception()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);
        $handler  = new LogHandler($logger->reveal());

        $logger->log(LogLevel::CRITICAL, Argument::type('string'))->shouldBeCalled();

        $handler->setException(new \Exception());

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }

    /**
     * @dataProvider errorProvider
     * @test
     */
    public function it_logs_an_error_with_an_appropriate_level($severity, $logLevel)
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);
        $handler  = new LogHandler($logger->reveal());

        $logger->log($logLevel, Argument::type('string'))->shouldBeCalled();

        $handler->setException(new \ErrorException('', 0, $severity));

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }

    public function errorProvider(): array
    {
        return [
            [E_ERROR, LogLevel::ERROR],
            [E_RECOVERABLE_ERROR, LogLevel::ERROR],
            [E_CORE_ERROR, LogLevel::ERROR],
            [E_COMPILE_ERROR, LogLevel::ERROR],
            [E_USER_ERROR, LogLevel::ERROR],
            [E_PARSE, LogLevel::ERROR],

            [E_WARNING, LogLevel::WARNING],
            [E_USER_WARNING, LogLevel::WARNING],
            [E_CORE_WARNING, LogLevel::WARNING],
            [E_COMPILE_WARNING, LogLevel::WARNING],

            [E_NOTICE, LogLevel::NOTICE],
            [E_USER_NOTICE, LogLevel::NOTICE],

            [E_STRICT, LogLevel::INFO],
            [E_DEPRECATED, LogLevel::INFO],
            [E_USER_DEPRECATED, LogLevel::INFO],

            [E_ALL + 1, LogLevel::CRITICAL],
        ];
    }
}
