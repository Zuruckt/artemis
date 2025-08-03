<?php declare(strict_types=1);

namespace App\Core\Http;

use App\Core\Http\Routing\ControllerInvoker;
use App\Core\Http\Routing\AppRouter;
use App\Core\Http\Server\Factories\MiddlewareHandlerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application implements RequestHandlerInterface
{
    public function __construct(
        private readonly AppRouter                $router,
        private readonly MiddlewareHandlerFactory $handlerFactory
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $match = $this->router->match($request);

        if ($match instanceof ResponseInterface) {
            return $match;
        }

        $invoker = new ControllerInvoker($match);
        return $this->handlerFactory->make($match->route->middleware, $invoker)
            ->handle($request);
    }
}
