<?php

namespace App\Core\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OutputHeader implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        header(\sprintf('%s: %s', 'X-Foo', 'Bar'), false);

        return $handler->handle($request);
    }
}