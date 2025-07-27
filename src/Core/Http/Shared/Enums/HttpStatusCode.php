<?php declare(strict_types=1);

namespace App\Core\Http\Shared\Enums;

enum HttpStatusCode: int
{
    case HTTP_OK = 200;
    case HTTP_NOT_FOUND = 404;

    case HTTP_SERVER_ERROR = 500;
}
