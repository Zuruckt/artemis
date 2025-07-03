<?php

namespace App\Core;

use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

final class Application
{
    private function handleRequest()
    {
        
    }
    public function build(): void
    {
        ds('server started');
        $http = new Server('0.0.0.0', 9000);

        $http->on('Request', function (Request $request, Response $response) {

            $serverRequest = ServerRequestFactory::fromSwooleRequest($request);

            $dispatcher = new Dispatcher();

            $middlewareStack = [
                new OutputHeader,
                new VerifyToken,
            ];

            $middlewareHandler = new MiddlewareHandler($middlewareStack, $dispatcher);

            $res = $middlewareHandler->handle($serverRequest);

            $response->setStatusCode($res->getStatusCode());

            foreach ($res->getHeaders() as $name => $values) {
                ds($res->getHeaders());
                foreach ($values as $value) {
                    $response->header($name, $value);
                }
            }

            $response->end($res->getBody()->getContents());
        });

        $http->start();

    }
}