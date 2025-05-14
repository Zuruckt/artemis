<?php

namespace Tests\Integration;

use Tests\TestCase;

class HelloWorldTest extends TestCase
{

    public function test_it_should_print_hello_world(): void
    {
        $response = (string) file_get_contents($this->baseUrl);

        self::assertStringContainsString('Hello World!', $response);
    }
}