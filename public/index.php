<?php

declare(strict_types=1);

use App\Core\Http\Application;
use App\Core\Http\Routing\AppRouter;
use App\Core\Http\Server\Factories\MiddlewareHandlerFactory;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use Dotenv\Dotenv;
use Swoole\Http\Server;

require __DIR__ . '/../vendor/autoload.php';

$router = AppRouter::createFromRoutingFile(__DIR__ . '/../routes/web.php');

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

$app = new Application($router, new MiddlewareHandlerFactory);

$server = new Server('0.0.0.0', (int) $_ENV['APP_SWOOLE_SERVER_PORT']);
$strategy = new HttpServerStrategy($app, $server);

$strategy->start();
