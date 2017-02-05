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
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $handler  = new ProductionHandler($twig->reveal(), true);

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * @test
     */
    public function it_is_skipped_when_debug_is_enabled()
    {
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $handler  = new ProductionHandler($twig->reveal(), true);

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }

    /**
     * @test
     */
    public function it_renders_a_generic_error_page_and_quits()
    {
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $handler  = new ProductionHandler($twig->reveal(), false);

        $twig->render('error/error500.html.twig')->willReturn('error');

        ob_start();
        $level = ob_get_level();

        $result = $handler->handle();

        $this->assertEquals('error', ob_get_contents());
        $this->assertEquals(Handler::QUIT, $result);

        while (ob_get_level() >= $level) {
            ob_end_clean();
        }
    }
}
