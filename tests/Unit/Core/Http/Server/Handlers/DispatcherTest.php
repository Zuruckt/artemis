<?php declare(strict_types=1);

namespace Tests\Unit\Core\Http\Server\Handlers;

use App\Core\Http\Server\Handlers\Dispatcher;
use App\Core\Http\Shared\Enums\HttpStatusCode;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(Dispatcher::class)]
class DispatcherTest extends TestCase
{
    #[DataProvider('endpointsProvider')]
    public function test_dispatcher_returns_adequate_response_from_request(string $uri, string $method, ResponseInterface $response): void
    {
        $dispatcher = new Dispatcher();

        $request = new ServerRequest(uri: $uri, method: $method);

        $dispatcherResponse = $dispatcher->handle($request);

        self::assertSame($response->getBody()->getContents(), $dispatcherResponse->getBody()->getContents());
        self::assertSame($response->getStatusCode(), $dispatcherResponse->getStatusCode());
    }

    /**
     * @return array<int, array{'uri': string, 'method': string, 'response': ResponseInterface}>
     */
    public static function endpointsProvider(): array
    {
        return [
            [
                'uri' => 'http://fake.url/hello',
                'method' => 'GET',
                'response' => new JsonResponse(['foo' => 'Hello World!'], HttpStatusCode::HTTP_OK->value),
            ],
            [
                'uri' => 'http://fake.url/hello',
                'method' => 'POST',
                'response' => new JsonResponse([], HttpStatusCode::HTTP_NOT_FOUND->value),
            ],
            [
                'uri' => 'http://fake.url/unknown',
                'method' => 'GET',
                'response' => new JsonResponse([], HttpStatusCode::HTTP_NOT_FOUND->value),
            ],
            [
                'uri' => 'http://fake.url/unknown',
                'method' => 'POST',
                'response' => new JsonResponse([], HttpStatusCode::HTTP_NOT_FOUND->value),
            ],
        ];
    }
}
