<?php

namespace App\Core\Http\Server\Factories;

use Laminas\Diactoros\ServerRequestFactory as LaminasServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Request;

class ServerRequestFactory extends LaminasServerRequestFactory
{
    public static function fromSwooleRequest(Request $swooleRequest): ServerRequestInterface
    {
        return self::fromGlobals(
            array_change_key_case($swooleRequest->server, CASE_UPPER),
            $swooleRequest->get,
            $swooleRequest->post,
            $swooleRequest->cookie,
            $swooleRequest->files,
        );
    }
}