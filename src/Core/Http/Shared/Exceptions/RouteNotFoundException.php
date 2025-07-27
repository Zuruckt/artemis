<?php declare(strict_types=1);

namespace App\Core\Http\Shared\Exceptions;

use App\Core\Http\Shared\Enums\HttpStatusCode;

class RouteNotFoundException extends \Exception
{
    protected $message = 'Route not found';
    protected $code = HttpStatusCode::HTTP_NOT_FOUND;
}