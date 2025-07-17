<?php

namespace Tests\Unit\Core\Http;

use App\Core\Http\Kernel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(Kernel::class)]
class KernelTest extends TestCase
{
    public function test_kernel_runs_through_middleware_stack_and_dispatcher()
    {
        $kernel =\Mockery::spy(Kernel::class);
        $mockRequest = $this->createMock(ServerRequestInterface::class);
        $mockHandler = $this->createMock(RequestHandlerInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockHandler->expects($this->once())->method('handle')->willReturn($mockResponse);

        $kernel->shouldAllowMockingProtectedMethods();
        $kernel->shouldReceive('prepareHandler')->andReturn($mockHandler)->once();

        $kernel->boot();
        $response = $kernel->handle($mockRequest);

        $this->assertSame($response, $mockResponse);
    }
}
