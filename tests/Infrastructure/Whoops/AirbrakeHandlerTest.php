<?php

namespace Tests\Nofw\Infrastructure\Whoops;

use Airbrake\Notifier;
use Nofw\Infrastructure\Whoops\AirbrakeHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Whoops\Handler\Handler;
use Whoops\Handler\HandlerInterface;

final class AirbrakeHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_handler()
    {
        /** @var Notifier|ObjectProphecy $airbrake */
        $airbrake = $this->prophesize(Notifier::class);
        $handler  = new AirbrakeHandler($airbrake->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * @test
     */
    public function it_notifies_airbrake_about_an_exception()
    {
        /** @var Notifier|ObjectProphecy $airbrake */
        $airbrake = $this->prophesize(Notifier::class);
        $handler  = new AirbrakeHandler($airbrake->reveal());

        $exception = new \Exception();

        $airbrake->notify($exception)->shouldBeCalled();

        $handler->setException($exception);

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }
}
