<?php

namespace Tests\Unit\Core\Http\Routing;


use App\Core\Http\Routing\AppRouter;
use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\RouteMatch;
use App\Core\Http\Routing\Exceptions\InvalidRoutingConfiguration;
use App\Core\Http\Shared\Enums\HttpMethod;
use App\Core\Http\Shared\Enums\HttpStatusCode;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[CoversClass(AppRouter::class)]
#[CoversClass(Route::class)]
#[CoversClass(RouteMatch::class)]
#[CoversClass(InvalidRoutingConfiguration::class)]
class AppRouterTest extends TestCase
{
    private string $validRouteFile;
    private string $invalidRouteFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validRouteFile = sys_get_temp_dir() . '/valid_routes_' . uniqid() . '.php';
        $this->invalidRouteFile = sys_get_temp_dir() . '/invalid_routes_' . uniqid() . '.php';

        file_put_contents($this->validRouteFile, '<?php return [new \App\Core\Http\Routing\Route(\App\Core\Http\Shared\Enums\HttpMethod::GET, "/hello", "hello", [])];');

        file_put_contents($this->invalidRouteFile, '<?php return "invalid";');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->validRouteFile)) {
            unlink($this->validRouteFile);
        }
        if (file_exists($this->invalidRouteFile)) {
            unlink($this->invalidRouteFile);
        }
        
        parent::tearDown();
    }

    public function test_it_registers_and_matches_a_route(): void
    {
        $route = new Route(HttpMethod::GET, '/hello', 'hello', []);
        $router = new AppRouter([$route]);

        $request = new ServerRequest([], [], '/hello', 'GET');

        $match = $router->match($request);

        $this->assertInstanceOf(RouteMatch::class, $match);
        $this->assertSame($route, $match->route);
        $this->assertSame([], $match->params);
    }

    public function test_it_matches_route_with_params(): void
    {
        $route = new Route(HttpMethod::GET, '/user/{id}', 'user.show', []);
        $router = new AppRouter([$route]);

        $request = new ServerRequest([], [], '/user/42', 'GET');
        $match = $router->match($request);

        $this->assertInstanceOf(RouteMatch::class, $match);
        $this->assertSame(['id' => '42'], $match->params);
    }

    public function test_it_returns_404_if_path_does_not_match(): void
    {
        $route = new Route(HttpMethod::GET, '/user/{id}', 'user.show', []);
        $router = new AppRouter([$route]);

        $request = new ServerRequest([], [], '/missing', 'GET');
        $match = $router->match($request);

        $this->assertInstanceOf(ResponseInterface::class, $match);
        $this->assertSame(HttpStatusCode::HTTP_NOT_FOUND->value, $match->getStatusCode());
    }

    public function test_it_returns_404_if_method_is_invalid(): void
    {
        $route = new Route(HttpMethod::GET, '/hello', 'hello', []);
        $router = new AppRouter([$route]);

        $request = new ServerRequest([], [], '/hello', 'DELETE');
        $match = $router->match($request);

        $this->assertInstanceOf(ResponseInterface::class, $match);
        $this->assertSame(HttpStatusCode::HTTP_NOT_FOUND->value, $match->getStatusCode());
    }

    public function test_it_can_create_router_from_valid_routing_file(): void
    {
        $router = AppRouter::createFromRoutingFile($this->validRouteFile);
        $this->assertInstanceOf(AppRouter::class, $router);
    }

    public function test_it_throws_on_invalid_routing_file(): void
    {
        $this->expectException(InvalidRoutingConfiguration::class);
        AppRouter::createFromRoutingFile($this->invalidRouteFile);
    }
}
