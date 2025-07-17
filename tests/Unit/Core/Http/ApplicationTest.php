<?php

namespace Tests\Unit\Core\Http;

use App\Core\Http\Application;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(Application::class)]
class ApplicationTest extends TestCase
{
    public function test_application_calls_handler(): void
    {
        $handlerSpy = \Mockery::spy(RequestHandlerInterface::class);
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $handlerSpy->shouldReceive('handle')
            ->andReturn($responseMock);

        $application = new Application($handlerSpy);

        $response = $application->handleRequest($requestMock);

        $handlerSpy->shouldHaveReceived('handle');
        $this->assertSame($responseMock, $response);
    }
}
