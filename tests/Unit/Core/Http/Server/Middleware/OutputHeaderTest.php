<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Http\Server\Middleware\OutputHeader;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(OutputHeader::class)]
class OutputHeaderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_header_is_sent_and_handler_called(): void
    {
        $serverRequest  = $this->createMock(ServerRequestInterface::class);
        $innerResponse = new JsonResponse(['foo' => 'bar']);

        $innerHandler = $this->createPartialMock(RequestHandlerInterface::class, ['handle']);
        $innerHandler->method('handle')
            ->with($serverRequest)
            ->willReturn($innerResponse);

        $expectedHeaders = [
            ...$innerResponse->getHeaders(),
            'X-Foo' => ['Bar'],
        ];
        $middleware      = new OutputHeader();
        $serverResponse  = $middleware->process($serverRequest, $innerHandler);

        self::assertSame($expectedHeaders, $serverResponse->getHeaders());
    }

    /**
     * @throws Exception
     */
    public function test_multiple_calls_do_not_duplicate_header(): void
    {
        $serverRequest  = $this->createMock(ServerRequestInterface::class);
        $innerResponse = new JsonResponse(['foo' => 'bar']);

        $innerHandler   = $this->createPartialMock(RequestHandlerInterface::class, ['handle']);
        $innerHandler->method('handle')->willReturn($innerResponse);

        $middleware = new OutputHeader();

        $middleware->process($serverRequest, $innerHandler);
        $serverResponse = $middleware->process($serverRequest, $innerHandler);

        $expectedHeaders = [
            ...$innerResponse->getHeaders(),
            'X-Foo' => ['Bar'],
        ];

        self::assertSame($expectedHeaders, $serverResponse->getHeaders());
    }
}
