<?php

namespace Tests\Unit\Core\Http\Routing;

use App\Core\Http\Routing\ControllerInvoker;
use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\RouteMatch;
use App\Core\Http\Shared\Enums\HttpMethod;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Http\Controllers\TestController;

#[CoversClass(ControllerInvoker::class)]
#[CoversClass(Route::class)]
#[CoversClass(RouteMatch::class)]
class ControllerInvokerTest extends TestCase
{
    public function test_it_invokes_handler_and_returns_response()
    {
        $route = new Route(
            method: HttpMethod::GET,
            path: '/users/{id}',
            name: 'user.show',
            handler: [TestController::class, 'show'],
        );

        $routeMatch = new RouteMatch($route, ['id' => 42]);
        $invoker = new ControllerInvoker($routeMatch);

        $request = new ServerRequest(['/users/42'], method: HttpMethod::GET->value);
        $response = $invoker->handle($request);

        $this->assertEquals(['id' => 42], json_decode($response->getBody(), true));
    }
}