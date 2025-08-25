<?php

namespace Tests\Unit\Core\Http\Routing;

use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\RouteMatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteMatch::class)]
class RouteMatchTest extends TestCase
{
    public function test_route_match_properties()
    {
        $route = $this->createMock(Route::class);

        $params = ['id' => '42'];

        $match = new RouteMatch($route, $params);

        $this->assertSame($route, $match->route);
        $this->assertEquals(['id' => '42'], $match->params);
    }
}
