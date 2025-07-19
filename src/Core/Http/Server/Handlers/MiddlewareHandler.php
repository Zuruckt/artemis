<?php declare(strict_types=1);

namespace App\Core\Http\Server\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplDoublyLinkedList;

final readonly class MiddlewareHandler implements RequestHandlerInterface
{
    /** @var SplDoublyLinkedList<MiddlewareInterface> $middlewareStack */
    private SplDoublyLinkedList $middlewareStack;

    /**
     * @param MiddlewareInterface[] $middlewareStack
     * @param RequestHandlerInterface $tail
     */
    public function __construct(

        array $middlewareStack,
        private RequestHandlerInterface $tail,
    )
    {
        $this->middlewareStack = new SplDoublyLinkedList();

        $reversedStack = array_reverse($middlewareStack);

        foreach ($reversedStack as $middleware) {
            $this->middlewareStack->push($middleware);
        }

        $this->middlewareStack->rewind();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatch($request);
    }

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->middlewareStack->valid()) {
            return $this->tail->handle($request);
        }

        $middleware = $this->middlewareStack->current();
        $this->middlewareStack->next();

        return $middleware->process($request, $this);
    }
}