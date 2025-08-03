<?php declare(strict_types=1);

namespace App\Core\Http\Routing;

use App\Core\Http\Shared\Enums\HttpMethod;
use App\Core\Http\Shared\Enums\HttpStatusCode;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WeakMap;

class AppRouter
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

    public function match(ServerRequestInterface $request): RouteMatch | ResponseInterface
    {
        $method = HttpMethod::tryFrom(strtoupper($request->getMethod()));
        $path = $request->getUri()->getPath();

        $notFound = new JsonResponse(['error' => 'Route note found'], HttpStatusCode::HTTP_NOT_FOUND->value);

        if (!$method) {
            return $notFound;
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

        return $notFound;
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