<?php declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloController
{
    public function sayHello(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'Hello!']);
    }

    public function showUser(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getAttribute('params', []);
        return new JsonResponse(['user_id' => $params['id'] ?? null]);
    }
}