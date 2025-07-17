<?php

namespace Tests\Unit;

use App\Core\Http\Application;
use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use Laminas\Diactoros\Response\JsonResponse;
use Mockery;
use RuntimeException;
use Tests\Mocks\Swoole\SwooleRequestMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Response;
use Swoole\Http\Server;

#[CoversClass(HttpServerStrategy::class)]
#[UsesClass(ServerRequestFactory::class)]
class HttpServerStrategyTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    public function test_strategy_should_start_server()
    {
        $server = Mockery::spy(Server::class);
        $application = Mockery::mock(Application::class);

        $serverStrategy = new HttpServerStrategy($application, $server);

        $serverStrategy->start();
        $server->shouldHaveReceived('start');
    }

    public function test_strategy_should_shutdown_server()
    {
        $server = Mockery::spy(Server::class);
        $application = $this->createMock(Application::class);

        $serverStrategy = new HttpServerStrategy($application, $server);

        $serverStrategy->shutdown();
        $server->shouldHaveReceived('shutdown');
    }

    public function test_strategy_registers_server_on_event()
    {
        $server = $this->createMock(Server::class);
        $application = $this->createMock(Application::class);
        $server->expects($this->once())
            ->method('on')
            ->with(
                $this->equalTo('request'),
                $this->callback(fn($callback) => is_array($callback) && $callback[0] instanceof HttpServerStrategy)
            );

        $serverStrategy = new HttpServerStrategy($application, $server);

        $serverStrategy->shutdown();
    }

    public function test_on_request_handles_request_to_application()
    {
        $application = $this->createMock(Application::class);
        $server = $this->createMock(Server::class);

        $responseBody = ['message' => 'hello'];
        $psrResponse = new JsonResponse($responseBody, 200);

        $application->method('handleRequest')
            ->willReturn($psrResponse);

        $strategy = new HttpServerStrategy($application, $server);

        $swooleRequest = new SwooleRequestMock;

        $swooleResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setStatusCode', 'header', 'end'])
            ->getMock();

        $swooleResponse->expects($this->once())
            ->method('setStatusCode')
            ->with(200);

        $swooleResponse->expects($this->exactly(1))
            ->method('header')
            ->with('content-type', 'application/json');

        $swooleResponse->expects($this->once())
            ->method('end')
            ->with(json_encode($responseBody));

        $strategy->onRequest($swooleRequest, $swooleResponse);
    }

    public function test_on_request_handles_thrown_exceptions()
    {
        $application = $this->createMock(Application::class);
        $application->method('handleRequest')
            ->willThrowException(new RuntimeException('Something went wrong', 0));

        $server = $this->createMock(Server::class);
        $strategy = new HttpServerStrategy($application, $server);

        $swooleRequest = new SwooleRequestMock();

        $swooleResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setStatusCode', 'header', 'end'])
            ->getMock();

        $swooleResponse->expects($this->once())
            ->method('setStatusCode')
            ->with(500);

        $swooleResponse->expects($this->once())
            ->method('end')
            ->with(json_encode(['error' => 'Something went wrong']));

        $strategy->onRequest($swooleRequest, $swooleResponse);
    }
}
