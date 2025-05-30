<?php
namespace App\Core\Http\Server\Handlers;

use App\Core\Http\Server\Handlers\MiddlewareHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class NextHandler implements RequestHandlerInterface
{
    public function __construct(private MiddlewareHandler $middlewareHandler, private int $index)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareHandler->dispatch($request, $this->index);
    }
}
