<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$dispatcher = new \App\Core\Http\Server\Handlers\Dispatcher();

$middlewareStack = [
    new \App\Core\Http\Server\Middleware\OutputHeader,
    new \App\Core\Http\Server\Middleware\VerifyToken,
];

$middlewareHandler = new \App\Core\Http\Server\Handlers\MiddlewareHandler($middlewareStack, $dispatcher);

$response = $middlewareHandler->handle($request);

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $response->getBody();
