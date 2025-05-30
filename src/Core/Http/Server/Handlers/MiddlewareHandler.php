<?php

namespace App\Core\Http\Server\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class MiddlewareHandler implements RequestHandlerInterface
{
    public function __construct(
        /** @var MiddlewareInterface[] $middlewareStack */
        private array                   $middlewareStack = [],
        private RequestHandlerInterface $tail,
    )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatch($request, 0);
    }

    public function dispatch(ServerRequestInterface $request, int $index): ResponseInterface
    {
        if (!isset($this->middlewareStack[$index])) {
            return $this->tail->handle($request);
        }

        $middleware = $this->middlewareStack[$index];

        $next = new NextHandler($this, $index + 1);
        return $middleware->process($request, $next);
    }
}