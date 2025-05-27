<?php

namespace App\Core\Http\Server\Handlers;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Dispatcher implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'GET' && $request->getUri()->getPath() === '/hello') {
            return new Response\JsonResponse(['foo' => 'Hello World!']);
        }

        return new Response(status: 404);
    }
}