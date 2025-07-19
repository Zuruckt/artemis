<?php declare(strict_types=1);

namespace App\Core\Swoole\Contracts;

interface ServerStrategy
{
    public function start(): void;

    public function shutdown(): bool;

    public function registerEvents(): void;
}