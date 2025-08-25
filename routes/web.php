<?php

declare(strict_types=1);

use App\Core\Http\Routing\Route;
use App\Core\Http\Server\Middleware\VerifyToken;
use App\Core\Http\Shared\Enums\HttpMethod;
use App\HelloController;

/** @returns Route[] */
return [
    new Route(
        HttpMethod::GET,
        '/hello',
        'hello',
        [HelloController::class, 'sayHello'],
        [VerifyToken::class]
    ),
    new Route(
        HttpMethod::GET,
        '/user/{id}',
        'user.show',
        [HelloController::class, 'showUser']
    )
];