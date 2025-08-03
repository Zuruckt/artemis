<?php declare(strict_types=1);

namespace App\Core\Http\Routing;

readonly class RouteMatch
{
    public function __construct(
        public Route $route,
        public array $params,
    )
    {
    }
}