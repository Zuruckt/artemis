<?php declare(strict_types=1);

namespace App\Core\Http\Server\Factories;

use App\Core\Http\Server\Handlers\MiddlewareHandler;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MiddlewareHandlerFactory
{
    /**
     * @param class-string<MiddlewareInterface>[] $middleware
     */
    public function make(array $middleware, RequestHandlerInterface $handler): RequestHandlerInterface
    {
        $instances = array_map(static fn(string $class): MiddlewareInterface => new $class(), $middleware);
        return new MiddlewareHandler($instances, $handler);
    }
}
