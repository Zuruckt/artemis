<?php

namespace App\Core\Http;

use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Kernel implements RequestHandlerInterface
{
    private RequestHandlerInterface $handler;

    /** @var array<class-string<\Psr\Http\Server\MiddlewareInterface>> */
    protected array $middlewareStack = [
        OutputHeader::class,
        VerifyToken::class,
    ];

    public function boot(): self
    {
        $this->handler = $this->createHandler();
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!isset($this->handler)) {
            throw new \RuntimeException("Kernel not booted. Call ->boot() before handling requests.");
        }

        return $this->handler->handle($request);
    }

    protected function createHandler(): RequestHandlerInterface
    {
        $middleware = array_map(fn($class) => new $class(), $this->middlewareStack);

        return new MiddlewareHandler($middleware, new Dispatcher());
    }
}
