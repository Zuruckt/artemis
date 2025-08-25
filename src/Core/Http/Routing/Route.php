<?php declare(strict_types=1);

namespace App\Core\Http\Routing;

use App\Core\Http\Shared\Enums\HttpMethod;
use Psr\Http\Server\MiddlewareInterface;

readonly class Route
{
    public function __construct(
        public HttpMethod $method,
        public string $path,
        public string $name,
        public array $handler,
        /** @var MiddlewareInterface $middleware */
        public array $middleware = [],
        public array $params = [],
    ) {}
}