<?php

namespace App\Core\Swoole\Strategies;

use App\Core\Http\Application;
use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Http\Shared\Enums\HttpStatusCode;
use App\Core\Swoole\Contracts\ServerStrategy;
use Laminas\Diactoros\Response\JsonResponse;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServerStrategy implements ServerStrategy
{
    public function __construct(private Application $app, private int $port)
    {
    }

    public function start(): void
    {
        $server = $this->createServer();

        $server->on('Request', function (Request $request, Response $response) {
            $serverRequest = ServerRequestFactory::fromSwooleRequest($request);

            try {
                $responseInterface = $this->app->handleRequest($serverRequest);
            } catch (\Exception $exception) {
                // TODO: actual exception handler
                $exceptionCode = $exception->getCode();
                $minCodeValue = 100;
                $statusCode =  $exceptionCode >= $minCodeValue ? $exceptionCode : HttpStatusCode::HTTP_SERVER_ERROR->value;

                $responseInterface = new JsonResponse(['error' => $exception->getMessage()], $statusCode);
            }

            $response->setStatusCode($responseInterface->getStatusCode());

            foreach ($responseInterface->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    $response->header($name, $value);
                }
            }

            $response->end($responseInterface->getBody()->getContents());
        });

        $server->start();
    }

    /**
     * @return Server
     */
    protected function createServer(): Server
    {
        return new Server("0.0.0.0", $this->port);
    }
}