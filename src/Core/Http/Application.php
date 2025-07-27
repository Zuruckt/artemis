<?php declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Http\Routing\ControllerInvoker;
use App\Core\Http\Routing\Router;
use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use App\Core\Http\Shared\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application implements RequestHandlerInterface
{
    public function __construct(private Router $router)
    {
    }

    /**
     * @throws RouteNotFoundException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $match = $this->router->match($request);

        if (!$match) {
            throw new RouteNotFoundException;
        }

        $invoker = new ControllerInvoker($match);
        $middlewareHandler = $this->createHandler($match->route->middleware, $invoker);

        return $middlewareHandler->handle($request);
    }

    /**
     * @param class-string[] $middleware
     */
    protected function createHandler(array $middleware, RequestHandlerInterface $invoker): RequestHandlerInterface
    {
        $middlewareInstances = array_map(static fn($class): MiddlewareInterface => new $class(), $middleware);

        return new MiddlewareHandler($middlewareInstances, $invoker);
    }
}
