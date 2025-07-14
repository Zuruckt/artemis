<?php

namespace App\Core\Swoole\Strategies;

use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use App\Core\Swoole\Contracts\ServerStrategy;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServerStrategy implements ServerStrategy
{
    private Server $server;
    private array $middlewareStack = [
        OutputHeader::class,
        VerifyToken::class,
    ];

    private MiddlewareHandler $middlewareHandler;

    // todo: accept Application as dependecy?
    public function __construct(string $host, int $port)
    {
        $this->prepareHandlers();
        $this->prepareServer($host, $port);
    }

    public function prepareServer(string $host, $port): void
    {
        $this->server = new Server($host, $port);

        $this->server->on('Request', function (Request $request, Response $response) {

            $serverRequest = ServerRequestFactory::fromSwooleRequest($request);

            // $this->>application->requestHandler?
            $responseInterface = $this->middlewareHandler->handle($serverRequest);

            $response->setStatusCode($responseInterface->getStatusCode());

            foreach ($responseInterface->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    $response->header($name, $value);
                }
            }

            $response->end($responseInterface->getBody()->getContents());
        });
    }

    // TODO: move back to application
    public function prepareHandlers(): void
    {
        $middleware = array_map(function (string $className) {
            return new $className();
        }, $this->middlewareStack);

        $this->middlewareHandler = new MiddlewareHandler($middleware, new Dispatcher());
    }

    public function start(): void
    {
        $this->server->start();
    }
}