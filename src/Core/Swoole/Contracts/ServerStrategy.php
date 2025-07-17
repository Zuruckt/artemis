<?php

namespace App\Core\Swoole\Contracts;

interface ServerStrategy
{
    public function start(): void;

    public function shutdown(): bool;

    public function registerEvents(): void;
}