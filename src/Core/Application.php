<?php

namespace App\Core;

use App\Core\Swoole\Contracts\ServerStrategy;

final readonly class Application
{
    public function __construct(private ServerStrategy $strategy)
    {
    }

    public function start(): void
    {
        $this->strategy->start();
    }
}