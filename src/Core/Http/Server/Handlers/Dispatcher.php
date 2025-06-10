<?php

namespace App\Core\Http\Server\Handlers;

use App\Core\Http\Shared\Enums\HttpStatusCodeEnum;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
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
            return new JsonResponse(['foo' => 'Hello World!']);
        }

        return new JsonResponse([], status: HttpStatusCodeEnum::HTTP_NOT_FOUND->value);
    }
}