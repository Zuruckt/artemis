<?php

namespace Tests\Unit\Core\Http\Routing;

use App\Core\Http\Routing\Route;
use App\Core\Http\Shared\Enums\HttpMethod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;

#[CoversClass(Route::class)]
class RouteTest extends TestCase
{
    public function test_route_properties_are_set_correctly()
    {
        $route = new Route(
            method: HttpMethod::GET,
            path: '/users',
            name: 'user.index',
            handler: ['UserController', 'index'],
            middleware: [MiddlewareInterface::class],
            params: ['id' => 123],
        );

        $this->assertEquals(HttpMethod::GET, $route->method);
        $this->assertEquals('/users', $route->path);
        $this->assertEquals('user.index', $route->name);
        $this->assertEquals(['UserController', 'index'], $route->handler);
        $this->assertSame([MiddlewareInterface::class], $route->middleware);
        $this->assertEquals(['id' => 123], $route->params);
    }
}
