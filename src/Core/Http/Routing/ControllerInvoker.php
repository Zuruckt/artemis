<?php declare(strict_types=1);

namespace App\Core\Http\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ControllerInvoker implements RequestHandlerInterface
{
    public function __construct(private RouteMatch $routeMatch) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        [$class, $method] = $this->routeMatch->route->handler;
        $instance = new $class();
        $request = $request->withAttribute('params', $this->routeMatch->params);
        return $instance->$method($request);
    }
}