<?php

namespace App\Core\Swoole\Contracts;

interface ServerStrategy
{
    public function start(): void;
}