<?php declare(strict_types=1);

namespace App\Core\Http\Routing;

use App\Core\Http\Shared\Enums\HttpMethod;
use Psr\Http\Message\ServerRequestInterface;
use WeakMap;

class Router
{
    /** @var WeakMap<HttpMethod, Route[]> $routes */
    private WeakMap $routes;

    public function __construct()
    {
        $this->routes = new WeakMap();
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method] = [];
        }
    }

    public function register(Route $route): void
    {
        $method = $route->method;
        $this->routes[$method][] = $route;
    }

    public function match(ServerRequestInterface $request): RouteMatch | false
    {
        $method = HttpMethod::tryFrom(strtoupper($request->getMethod()));
        $path = $request->getUri()->getPath();

        if (!$method) {
            return false;
        }

        $methodRoutes = $this->routes[$method] ??= [];

        /** @var Route $route */
        foreach ($methodRoutes as $route) {

            $params = $this->matchAndExtractParams($path, $route);
            if ($params !== false) {

                return new RouteMatch(
                    $route,
                    $params,
                );
            }
        }

        return false;
    }

    public function matchAndExtractParams(string $path, Route $route): array | false
    {
        $pattern = preg_replace('#\{([^}]+)}#', '(?P<$1>[^/]+)', $route->path);
        $regex = '#^' . $pattern . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return false;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}