<?php declare(strict_types=1);

namespace App\Core\Swoole\Strategies;

use App\Core\Http\Application;
use App\Core\Http\Server\Factories\ServerRequestFactory;
use App\Core\Http\Shared\Enums\HttpStatusCode;
use App\Core\Swoole\Contracts\ServerStrategy;
use Laminas\Diactoros\Response\JsonResponse;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class HttpServerStrategy implements ServerStrategy
{

    public function __construct(private readonly Application $app, private readonly Server $server)
    {
        $this->registerEvents();
    }

    public function start(): void
    {
        $this->server->start();
    }

    public function shutdown(): bool
    {
        return $this->server->shutdown();
    }
    public function registerEvents(): void
    {
        $this->server->on('request', [$this, 'onRequest']);
    }

    public function onRequest(Request $request, Response $response): void
    {
        $serverRequest = ServerRequestFactory::fromSwooleRequest($request);

        try {
            $responseInterface = $this->app->handle($serverRequest);
        } catch (\Exception $exception) {
            // TODO: actual exception handler
            $exceptionCode = $exception->getCode();
            $minCodeValue = 100;
            $statusCode = $exceptionCode >= $minCodeValue ? $exceptionCode : HttpStatusCode::HTTP_SERVER_ERROR->value;

            $responseInterface = new JsonResponse(['error' => $exception->getMessage()], $statusCode);
        }

        $response->setStatusCode($responseInterface->getStatusCode());

        foreach ($responseInterface->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $response->header($name, $value);
            }
        }

        $response->end($responseInterface->getBody()->getContents());
    }
}