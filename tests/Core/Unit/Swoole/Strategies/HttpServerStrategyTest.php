<?php

namespace Tests\Unit;

use App\Core\Http\Application;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use http\Env\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Server;

#[CoversClass(HttpServerStrategy::class)]
class HttpServerStrategyTest extends TestCase
{
    public function test_server_registers_request_event()
    {
        $mockApp = \Mockery::spy(Application::class);
        $mockApp->shouldReceive('handleRequest')
            ->once()
            ->andReturn($this->createMock(ResponseInterface::class));

        $serverMock = \Mockery::spy(Server::class);

        $serverMock->shouldReceive('start')
            ->andReturn()
            ->once();

        $mockStrategy = $this->createPartialMock(HttpServerStrategy::class, ['createServer']);

        $mockStrategy->expects($this->once())
            ->method('createServer')
            ->willReturn($serverMock);

        $mockStrategy->start();

        $serverMock->shouldHaveReceived('on');
    }
}
