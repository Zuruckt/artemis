<?php

namespace Tests\Unit;

use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Server as SwooleServer;

#[CoversClass(HttpServerStrategy::class)]
#[CoversClass(MiddlewareHandler::class)]
#[CoversMethod(HttpServerStrategy::class, 'prepareServer')]
class HttpServerStrategyTest extends TestCase
{
    public function test_server_is_initialized_with_host_and_port(): void
    {
        $strategy = new HttpServerStrategy('0.0.0.0', 9501);

    }

    public function test_middleware_handler_is_prepared(): void
    {
        $strategy = $this->getMockBuilder(HttpServerStrategy::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepareServer'])
            ->getMock();

        $reflection = new \ReflectionClass(HttpServerStrategy::class);
        $method = $reflection->getMethod('prepareHandlers');
        $method->setAccessible(true);
        $method->invoke($strategy);

        $middlewareHandlerProp = $reflection->getProperty('middlewareHandler');
        $middlewareHandlerProp->setAccessible(true);
        $middleware_handler = $middlewareHandlerProp->getValue($strategy);

        $this->assertInstanceOf(MiddlewareHandler::class, $middleware_handler);
    }
}
