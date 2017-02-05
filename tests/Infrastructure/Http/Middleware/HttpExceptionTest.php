<?php

namespace Tests\Nofw\Infrastructure\Http\Middleware;

use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\Delegate;
use Nofw\Infrastructure\Http\Exception\NotFoundException;
use Nofw\Infrastructure\Http\Middleware\HttpException;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

final class HttpExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_middleware()
    {
        $middleware = new HttpException();

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    /**
     * @test
     */
    public function it_converts_an_http_exception_to_response()
    {
        $middleware = new HttpException();
        $delegate = new Delegate(function() {
            throw new NotFoundException();
        });

        $response = $middleware->process(new ServerRequest(), $delegate);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_does_not_convert_a_nono_http_exception_to_response()
    {
        $this->expectException(\RuntimeException::class);

        $middleware = new HttpException();
        $delegate = new Delegate(function() {
            throw new \RuntimeException();
        });

        $middleware->process(new ServerRequest(), $delegate);
    }
}
