<?php

namespace App\Core;

use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Server\Handlers\MiddlewareHandler;
use App\Core\Http\Server\Middleware\OutputHeader;
use App\Core\Http\Server\Middleware\VerifyToken;
use Dotenv\Dotenv;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

final class Application
{
    const string HOST = '0.0.0.0';
    private Server $server;

    private array $middlewareStack = [
        OutputHeader::class,
        VerifyToken::class,
    ];
    private MiddlewareHandler $middlewareHandler;

    public function __construct()
    {
        $this->loadEnvironment();
        $this->prepareHandlers();
        $this->prepareServer();
    }

    public function start(): void
    {
        $this->server->start();
    }

    private function loadEnvironment(): void
    {
        $env = Dotenv::createImmutable(__DIR__ . '/../../');
        $env->load();
    }

    /**
     * @return void
     */
    public function prepareHandlers(): void
    {
        $middleware = array_map(function (string $className) {
            return new $className();
        }, $this->middlewareStack);

        $this->middlewareHandler = new MiddlewareHandler($middleware, new Dispatcher());
    }

    /**
     * @return void
     */
    public function prepareServer(): void
    {
        $this->server = new Server(self::HOST, $_ENV['APP_SWOOLE_SERVER_PORT']);

        $this->server->on('Request', function (Request $request, Response $response) {

            $serverRequest = ServerRequestFactory::fromSwooleRequest($request);

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
}