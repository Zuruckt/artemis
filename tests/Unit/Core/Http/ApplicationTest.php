<?php declare(strict_types=1);

namespace Tests\Unit\Core\Http;

use App\Core\Http\Application;
use Laminas\Diactoros\ServerRequest;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(Application::class)]
class ApplicationTest extends TestCase
{
    public function test_applications_produces_response_from_incoming_request(): void
    {
        $applicationSpy = Mockery::spy(Application::class)->makePartial();
        $handlerMock = Mockery::spy(RequestHandlerInterface::class);
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);


        $applicationSpy->shouldAllowMockingProtectedMethods();
        $applicationSpy->shouldReceive('createHandler')->andReturn($handlerMock);

        $handlerMock->shouldReceive('handle')->andReturn($responseMock);

        $response = $applicationSpy->handle($requestMock);

        self::assertSame($responseMock, $response);
    }
}
