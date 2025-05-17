<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

class HelloWorldTest extends TestCase
{
    private Client $client;
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    /**
     * @throws GuzzleException
     */
    public function test_it_should_print_hello_world(): void
    {
        $response = $this->client->get('/');

        self::assertStringContainsString('Hello World!', $response->getBody());
    }
}
