<?php

namespace App\Core\Http\Routing\Exceptions;

use App\Core\Http\Routing\Route;
use Exception;

class InvalidRoutingConfiguration extends Exception
{
    public static function invalidRouteArray(string $path): self
    {
        $routeClassName = Route::class;
        return new self("$path must return an array of $routeClassName");
    }
}