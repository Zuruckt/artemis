<?php

namespace App\Core\Http;

use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application implements RequestHandlerInterface
{
    /** @var array<class-string<MiddlewareInterface>> */
    protected array $middlewareStack = [
        VerifyToken::class,
        OutputHeader::class,
    ];

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->createHandler();
        return $handler->handle($request);
    }

    protected function createHandler(): RequestHandlerInterface
    {
        // TODO: create router and move middleware to route specific
        $middleware = array_map(fn($class): MiddlewareInterface => new $class(), $this->middlewareStack);

        return new MiddlewareHandler($middleware, new Dispatcher());
    }
}
