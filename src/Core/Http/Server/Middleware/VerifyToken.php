<?php

namespace App\Core\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyToken implements MiddlewareInterface
{

    /**
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $query = $request->getQueryParams();

        if (empty($query['token'])) {
            throw new \Exception('Token not found in request');
        }

        return $handler->handle($request);
    }
}