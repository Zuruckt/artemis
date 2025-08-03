<?php

namespace Tests\Unit\Core\Http\Routing;

use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\AppRouter;
use App\Core\Http\Routing\RouteMatch;
use App\Core\Http\Shared\Enums\HttpMethod;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Http\Controllers\TestController;

#[CoversClass(AppRouter::class)]
#[CoversClass(Route::class)]
#[CoversClass(RouteMatch::class)]
final class AppRouterTest extends TestCase
{
    public function test_match_returns_route_match_for_registered_route(): void
    {
        $router = new AppRouter();

        $route = new Route(
            method: HttpMethod::GET,
            path: '/user/{id}',
            name: 'user.show',
            handler: [TestController::class, 'show'],
        );

        $router->register($route);

        $request = new ServerRequest()
            ->withMethod(HttpMethod::GET->value)
            ->withUri(new Uri('/user/123'));

        $match = $router->match($request);

        $this->assertInstanceOf(RouteMatch::class, $match);
        $this->assertSame($route, $match->route);
        $this->assertSame(['id' => '123'], $match->params);
    }

    public function test_match_returns_404_if_no_matching_route(): void
    {
        $router = new AppRouter();

        $route = new Route(
            method: HttpMethod::GET,
            path: '/user/{id}',
            name: 'user.show',
            handler: [TestController::class, 'show'],
        );

        $router->register($route);

        $request = new ServerRequest()
            ->withMethod(HttpMethod::GET->value)
            ->withUri(new Uri('/not-matching'));

        $result = $router->match($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertSame(404, $result->getStatusCode());
    }

    public function test_match_returns_404_for_invalid_method(): void
    {
        $router = new AppRouter();

        $request = new ServerRequest()
            ->withMethod('INVALID')
            ->withUri(new Uri('/user/123'));

        $result = $router->match($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertSame(404, $result->getStatusCode());
    }

    public function test_register_adds_route_to_internal_map(): void
    {
        $router = new AppRouter();

        $route = new Route(
            method: HttpMethod::GET,
            path: '/hello',
            name: 'greeting.hello',
            handler: [TestController::class, 'hello'],
        );

        $router->register($route);

        $request = new ServerRequest()
            ->withMethod(HttpMethod::GET->value)
            ->withUri(new Uri('/hello'));

        $match = $router->match($request);

        $this->assertInstanceOf(RouteMatch::class, $match);
        $this->assertSame($route, $match->route);
    }

}
