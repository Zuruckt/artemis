<?php

namespace Tests\Unit;

use App\Core\Http\Server\Middleware\VerifyToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(VerifyToken::class)]
class VerifyTokenTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_process_throws_exception_when_token_missing(): void
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->method('getQueryParams')
            ->willReturn([]);

        $innerHandler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new VerifyToken();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Token not found in request');

        $middleware->process($serverRequest, $innerHandler);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function test_process_calls_handler_when_token_present(): void
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->method('getQueryParams')
            ->willReturn(['token' => 'abc123']);

        $serverResponse = $this->createMock(ResponseInterface::class);

        $innerHandler = $this->createMock(RequestHandlerInterface::class);
        $innerHandler->expects(self::once())
            ->method('handle')
            ->with($serverRequest)
            ->willReturn($serverResponse);

        $middleware = new VerifyToken();
        $resultResponse = $middleware->process($serverRequest, $innerHandler);

        self::assertSame($serverResponse, $resultResponse);
    }
}
