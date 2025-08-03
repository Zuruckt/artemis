<?php

namespace Tests\Unit\Core\Http;

use App\Core\Http\Application;
use App\Core\Http\Routing\ControllerInvoker;
use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\RouteMatch;
use App\Core\Http\Routing\AppRouter;
use App\Core\Http\Server\Factories\MiddlewareHandlerFactory;
use App\Core\Http\Shared\Enums\HttpMethod;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\Fixtures\Http\Controllers\TestController;

#[CoversClass(Application::class)]
#[CoversClass(ControllerInvoker::class)]
#[CoversClass(Route::class)]
#[CoversClass(RouteMatch::class)]
class ApplicationTest extends TestCase
{
    public function test_handle_returns_router_response_directly(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = new JsonResponse(['error' => 'not found'], 404);

        $router = $this->createMock(AppRouter::class);
        $router->method('match')->with($request)->willReturn($response);

        $middlewareHandlerFactory = $this->createMock(MiddlewareHandlerFactory::class);

        $app = new Application($router, $middlewareHandlerFactory);

        $result = $app->handle($request);

        $this->assertSame($response, $result);
    }

    public function test_handle_delegates_to_factory_and_invoker(): void
    {
        $request = new ServerRequest([], [], '/users', 'GET');
        $expectedResponse = new JsonResponse(['message' => 'ok']);

        $route = new Route(
            HttpMethod::GET,
            '/users',
            'test.route',
            [TestController::class, 'index'],
            []
        );

        $match = new RouteMatch($route, []);

        $router = $this->createMock(AppRouter::class);
        $router->method('match')->willReturn($match);

        $mockHandler = $this->createMock(RequestHandlerInterface::class);
        $mockHandler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($expectedResponse);

        $factory = $this->createMock(MiddlewareHandlerFactory::class);
        $factory->expects($this->once())
            ->method('make')
            ->with($route->middleware, $this->isInstanceOf(ControllerInvoker::class))
            ->willReturn($mockHandler);

        $app = new Application($router, $factory);
        $actual = $app->handle($request);

        $this->assertSame($expectedResponse, $actual);
    }

}
