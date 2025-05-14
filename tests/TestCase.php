<?php

namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected string $baseUrl;
    protected function setUp(): void
    {
        $this->baseUrl = 'http://host.docker.internal:8000';
    }
}
