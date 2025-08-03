<?php

namespace Tests\Core\Http\Server\Factories;

use App\Core\Http\Server\Factories\MiddlewareHandlerFactory;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(MiddlewareHandlerFactory::class)]
#[CoversClass(MiddlewareHandler::class)]
class MiddlewareHandlerFactoryTest extends TestCase
{
    public function test_make_creates_middleware_handler_with_instances_without_dummy_classes(): void
    {
        $middleware1 = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middleware2 = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [
            get_class($middleware1),
            get_class($middleware2),
        ];

        $mockHandler = $this->createMock(RequestHandlerInterface::class);

        $factory = new MiddlewareHandlerFactory();

        $handler = $factory->make($middlewareClasses, $mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }
}
