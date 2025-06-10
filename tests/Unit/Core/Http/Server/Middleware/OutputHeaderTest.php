<?php

namespace Tests\Unit;

use App\Core\Http\Server\Middleware\OutputHeader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(OutputHeader::class)]
class OutputHeaderTest extends TestCase
{
    protected function setUp(): void
    {
        header_remove('X-Foo');
    }

    protected function tearDown(): void
    {
        header_remove('X-Foo');
    }

    /**
     * @throws Exception
     */
    public function test_header_is_sent_and_handler_called(): void
    {
        $serverRequest  = $this->createMock(ServerRequestInterface::class);
        $serverResponse = $this->createMock(ResponseInterface::class);

        $innerHandler = $this->createMock(RequestHandlerInterface::class);
        $innerHandler->expects(self::once())
            ->method('handle')
            ->with($serverRequest)
            ->willReturn($serverResponse);

        $middleware      = new OutputHeader();
        $resultResponse  = $middleware->process($serverRequest, $innerHandler);

        self::assertSame($serverResponse, $resultResponse);

        $sentHeaders = xdebug_get_headers();

        self::assertContains('X-Foo: Bar', $sentHeaders);
    }

    /**
     * @throws Exception
     */
    public function test_multiple_calls_do_not_duplicate_header(): void
    {
        $serverRequest  = $this->createMock(ServerRequestInterface::class);
        $serverResponse = $this->createMock(ResponseInterface::class);

        $innerHandler = $this->createMock(RequestHandlerInterface::class);
        $innerHandler->method('handle')->willReturn($serverResponse);

        $middleware = new OutputHeader();

        $middleware->process($serverRequest, $innerHandler);
        $middleware->process($serverRequest, $innerHandler);

        $sentHeaders = xdebug_get_headers();

        self::assertCount(
            1,
            array_filter($sentHeaders, static fn($header) => $header === 'X-Foo: Bar'),
            'Header should only appear once'
        );
    }
}
