<?php
declare(strict_types=1);

use App\Core\Router;

require __DIR__ . '/../vendor/autoload.php';

$router = new Router;

$router->helloWorld();