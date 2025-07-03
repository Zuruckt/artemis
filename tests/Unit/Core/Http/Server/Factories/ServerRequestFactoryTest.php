<?php declare(strict_types=1);

namespace Tests\Unit\Core\Http\Server\Factories;

use App\Core\Http\Server\Factories\ServerRequestFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Request as SwooleRequest;

#[CoversClass(ServerRequestFactory::class)]
class ServerRequestFactoryTest extends TestCase
{
    public function test_from_swoole_request_returns_server_request_interface(): void
    {
        $swooleRequest = $this->createMock(SwooleRequest::class);

        $swooleRequest->server = [
            'request_method' => 'GET',
            'request_uri' => '/test',
        ];

        $swooleRequest->get = ['foo' => 'bar'];
        $swooleRequest->post = ['baz' => 'qux'];
        $swooleRequest->cookie = ['session' => 'abc123'];
        $swooleRequest->files = [];

        $serverRequest = ServerRequestFactory::fromSwooleRequest($swooleRequest);

        self::assertInstanceOf(ServerRequestInterface::class, $serverRequest);
        self::assertSame('bar', $serverRequest->getQueryParams()['foo'] ?? null);
        self::assertSame('qux', $serverRequest->getParsedBody()['baz'] ?? null);
        self::assertSame('abc123', $serverRequest->getCookieParams()['session'] ?? null);
    }
}
