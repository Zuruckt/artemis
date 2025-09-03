<?php

namespace Tests\Unit\Core\Http\Server\Factories;

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
    private MiddlewareHandlerFactory $factory;
    private RequestHandlerInterface $mockHandler;

    protected function setUp(): void
    {
        $this->factory = new MiddlewareHandlerFactory();
        $this->mockHandler = $this->createMock(RequestHandlerInterface::class);
    }

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

        $handler = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }

    public function test_make_creates_middleware_handler_with_single_middleware(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [get_class($middleware)];

        $handler = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }

    public function test_make_creates_middleware_handler_with_empty_middleware_array(): void
    {
        $middlewareClasses = [];

        $handler = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }

    public function test_make_creates_middleware_handler_with_multiple_middleware(): void
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

        $middleware3 = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [
            get_class($middleware1),
            get_class($middleware2),
            get_class($middleware3),
        ];

        $handler = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }

    public function test_make_returns_different_instances_for_same_input(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [get_class($middleware)];

        $handler1 = $this->factory->make($middlewareClasses, $this->mockHandler);
        $handler2 = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler1);
        $this->assertInstanceOf(MiddlewareHandler::class, $handler2);
        $this->assertNotSame($handler1, $handler2);
    }

    public function test_make_creates_middleware_handler_with_different_request_handlers(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [get_class($middleware)];
        $mockHandler1 = $this->createMock(RequestHandlerInterface::class);
        $mockHandler2 = $this->createMock(RequestHandlerInterface::class);

        $handler1 = $this->factory->make($middlewareClasses, $mockHandler1);
        $handler2 = $this->factory->make($middlewareClasses, $mockHandler2);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler1);
        $this->assertInstanceOf(MiddlewareHandler::class, $handler2);
        $this->assertNotSame($handler1, $handler2);
    }

    public function test_make_creates_middleware_handler_with_complex_middleware_namespace(): void
    {
        // Test with a more complex namespace structure
        $middleware = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };

        $middlewareClasses = [get_class($middleware)];

        $handler = $this->factory->make($middlewareClasses, $this->mockHandler);

        $this->assertInstanceOf(MiddlewareHandler::class, $handler);
    }
}
