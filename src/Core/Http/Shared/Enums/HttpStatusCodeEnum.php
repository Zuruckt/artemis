<?php

namespace App\Core\Http\Shared\Enums;

enum HttpStatusCodeEnum: int
{
    case HTTP_OK = 200;
    case HTTP_NOT_FOUND = 404;
}
