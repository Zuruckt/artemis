<?php

namespace App\Core\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application
{
    public function __construct(
        protected RequestHandlerInterface $handler
    ) {}

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
