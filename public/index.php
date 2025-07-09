<?php

declare(strict_types=1);

use App\Core\Application;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

// Isso vai mudar em runtime?
$strategy = new \App\Core\Swoole\Strategies\HttpServerStrategy('0.0.0.0', $_ENV['APP_SWOOLE_SERVER_PORT']);

$app = new Application($strategy);
$app->start();
