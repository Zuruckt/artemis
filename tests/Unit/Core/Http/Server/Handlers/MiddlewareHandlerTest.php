<?php

namespace Tests\Unit;

use App\Core\Http\Server\Handlers\MiddlewareHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(MiddlewareHandler::class)]
class MiddlewareHandlerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_middleware_stack_is_processed_in_correct_order(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $tailHandler = $this->createMock(RequestHandlerInterface::class);
        $tailHandler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $middlewareOne = $this->createMock(MiddlewareInterface::class);
        $middlewareOne->expects(self::once())
            ->method('process')
            ->with($request, self::isInstanceOf(MiddlewareHandler::class))
            ->willReturnCallback(static function (ServerRequestInterface $req, RequestHandlerInterface $handler) {
                return $handler->handle($req);
            });

        $middlewareTwo = $this->createMock(MiddlewareInterface::class);
        $middlewareTwo->expects(self::once())
            ->method('process')
            ->with($request, self::isInstanceOf(MiddlewareHandler::class))
            ->willReturnCallback(static function (ServerRequestInterface $req, RequestHandlerInterface $handler) {
                return $handler->handle($req);
            });

        $middlewareStack = [$middlewareOne, $middlewareTwo];

        $handler = new MiddlewareHandler($middlewareStack, $tailHandler);
        $result = $handler->handle($request);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function test_tail_handler_is_called_when_no_middleware_is_provided(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $tail_handler = $this->createMock(RequestHandlerInterface::class);
        $tail_handler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $handler = new MiddlewareHandler([], $tail_handler);
        $result = $handler->handle($request);

        self::assertSame($response, $result);
    }

    /**
     * @throws Exception
     */
    public function test_middleware_stack_executes_in_reverse_order(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $callOrder = [];

        $tailHandler = $this->createMock(RequestHandlerInterface::class);
        $tailHandler->expects(self::once())
            ->method('handle')
            ->willReturnCallback(static function () use (&$callOrder, $response) {
                $callOrder[] = 'tail';
                return $response;
            });

        $middlewareOne = $this->createMock(MiddlewareInterface::class);
        $middlewareOne->expects(self::once())
            ->method('process')
            ->willReturnCallback(static function (ServerRequestInterface $req, RequestHandlerInterface $handler) use (&$callOrder) {
                $callOrder[] = 'middlewareOne';
                return $handler->handle($req);
            });

        $middlewareTwo = $this->createMock(MiddlewareInterface::class);
        $middlewareTwo->expects(self::once())
            ->method('process')
            ->willReturnCallback(static function (ServerRequestInterface $req, RequestHandlerInterface $handler) use (&$callOrder) {
                $callOrder[] = 'middlewareTwo';
                return $handler->handle($req);
            });

        $stack = [$middlewareOne, $middlewareTwo];
        $handler = new MiddlewareHandler($stack, $tailHandler);
        $result = $handler->handle($request);

        self::assertSame($response, $result);
        self::assertSame(
            ['middlewareTwo', 'middlewareOne', 'tail'],
            $callOrder
        );
    }
}
