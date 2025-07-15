<?php

declare(strict_types=1);

use App\Core\Http\Application;
use App\Core\Swoole\Strategies\HttpServerStrategy;
use App\Core\Http\Kernel;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

// Qual classe eu teria para orquestrar isso fora do index?

$kernel = new Kernel()->boot();
$app = new Application($kernel);
$strategy = new HttpServerStrategy($app, (int) $_ENV['APP_SWOOLE_SERVER_PORT']);

$strategy->start();
