<?php

namespace Tests\Nofw\Infrastructure\Http\Middleware;

use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\Delegate;
use Nofw\Infrastructure\Http\Middleware\ErrorPageContent;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

final class ErrorPageContentTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_middleware()
    {
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $middleware = new ErrorPageContent($twig->reveal());

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    /**
     * @test
     */
    public function it_does_not_modify_a_successful_request()
    {
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $middleware = new ErrorPageContent($twig->reveal());

        $response = new Response('php://memory', 200);
        $delegate = new Delegate(function() use ($response) {
            return $response;
        });

        $returnedResponse = $middleware->process(new ServerRequest(), $delegate);

        $this->assertSame($response, $returnedResponse);
    }

    /**
     * @dataProvider errorProvider
     * @test
     */
    public function it_creates_a_body_for_empty_error_responses($statusCode, $template, $context, $body)
    {
        /** @var \Twig_Environment|ObjectProphecy $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $middleware = new ErrorPageContent($twig->reveal());

        $delegate = new Delegate(function() use ($statusCode) {
            return new Response('php://memory', $statusCode);
        });

        if (is_array($context)) {
            $twig->render($template, $context)->willReturn($body);
        } else {
            $twig->render($template)->willReturn($body);
        }

        $response = $middleware->process((new ServerRequest())->withHeader('Accept', 'text/html'), $delegate);

        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }

    public function errorProvider(): array
    {
        return [
            [404, 'error/error404.html.twig', null, '404'],
            [
                500,
                'error/error.html.twig',
                [
                    'status_code' => 500,
                    'reason_phrase' => 'Internal Server Error',
                ],
                'error',
            ],
        ];
    }
}
