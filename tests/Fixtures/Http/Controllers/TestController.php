<?php

namespace Tests\Fixtures\Http\Controllers;


use App\Core\Http\Shared\Enums\HttpStatusCode;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController
{
    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('params')['id'] ?? 'unknown';
        return new JsonResponse(['id' => $id], HttpStatusCode::HTTP_OK->value);
    }
}