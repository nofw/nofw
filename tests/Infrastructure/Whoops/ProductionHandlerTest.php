<?php

namespace Tests\Nofw\Infrastructure\Whoops;

use Nofw\Infrastructure\Whoops\ProductionHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Whoops\Handler\Handler;
use Whoops\Handler\HandlerInterface;

final class ProductionHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_handler()
    {
        $handler  = new ProductionHandler(true);

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * @test
     */
    public function it_is_skipped_when_debug_is_enabled()
    {
        $handler  = new ProductionHandler(true);

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }

    /**
     * @test
     */
    public function it_renders_a_generic_error_page_and_quits()
    {
        $handler  = new ProductionHandler(false);

        $result = $handler->handle();

        $this->assertEquals(Handler::QUIT, $result);
    }
}
