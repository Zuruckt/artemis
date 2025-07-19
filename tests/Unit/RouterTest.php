<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Core\Router;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(Router::class)]
class RouterTest extends TestCase
{
    public function test_it_should_return_hello_world(): void
    {
        $router = new Router();
        self::assertStringContainsString('Hello World!', $router->helloWorld());
    }
}
