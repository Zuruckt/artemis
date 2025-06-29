<?php

declare(strict_types=1);

use Swoole\Http\Request;
use Swoole\Http\Response;

require __DIR__ . '/../vendor/autoload.php';

$http = new Swoole\Http\Server('0.0.0.0', 9000);

$http->on('Request', function (Request $request, Response $response) {
    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . '</h1>');

    // TODO: transform Swoole\Http\Request -> Psr\Http\Message\ServerRequestInterface
//    $dispatcher = new \App\Core\Http\Server\Handlers\Dispatcher();
//
//    $middlewareStack = [
//        new \App\Core\Http\Server\Middleware\OutputHeader,
//        new \App\Core\Http\Server\Middleware\VerifyToken,
//    ];
//
//    $middlewareHandler = new \App\Core\Http\Server\Handlers\MiddlewareHandler($middlewareStack, $dispatcher);
//
//    $res = $middlewareHandler->handle($request);
//
//    $response->setStatusCode($res->getStatusCode());
//
//    foreach ($res->getHeaders() as $name => $values) {
//        foreach ($values as $value) {
//            $response->header($name, $value);
//
//            header(sprintf('%s: %s', $name, $value), false);
//        }
//    }
//
//    $response->end($res->getBody()->getContents());
});

$http->start();



//
//$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
//
//$dispatcher = new \App\Core\Http\Server\Handlers\Dispatcher();
//
//$middlewareStack = [
//    new \App\Core\Http\Server\Middleware\OutputHeader,
//    new \App\Core\Http\Server\Middleware\VerifyToken,
//];
//
//$middlewareHandler = new \App\Core\Http\Server\Handlers\MiddlewareHandler($middlewareStack, $dispatcher);
//
//$response = $middlewareHandler->handle($request);
//
//http_response_code($response->getStatusCode());
//
//foreach ($response->getHeaders() as $name => $values) {
//    foreach ($values as $value) {
//        header(sprintf('%s: %s', $name, $value), false);
//    }
//}
//
//echo $response->getBody();
