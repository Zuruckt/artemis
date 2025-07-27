<?php

declare(strict_types=1);

use App\Core\Http\Application;
use App\Core\Http\Routing\Route;
use App\Core\Http\Routing\Router;
use App\Core\Http\Server\Middleware\VerifyToken;
use App\Core\Http\Shared\Enums\HttpMethod;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use App\HelloController;
use Dotenv\Dotenv;
use Swoole\Http\Server;

require __DIR__ . '/../vendor/autoload.php';


$router = new Router();

$router->register(new Route(
    HttpMethod::GET,
    '/hello',
    'hello',
    [HelloController::class, 'sayHello'],
    [VerifyToken::class]
));

$router->register(new Route(
    HttpMethod::GET,
    '/user/{id}',
    'user.show',
    [HelloController::class, 'showUser']
));

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

$app = new Application($router);

$server = new Server('0.0.0.0', (int) $_ENV['APP_SWOOLE_SERVER_PORT']);
$strategy = new HttpServerStrategy($app, $server);

$strategy->start();
