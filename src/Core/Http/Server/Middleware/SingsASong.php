<?php

namespace App\Core\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SingsASong implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $song = <<<TXT
            There's an axolotl in the pink stairs
            
            Is an axolotl supposed to be there?
            
            If you ask an axolotl
            
            If they'll be back tomorrow
            
            A penguin waddles in and then the axolotl's gone!
        TXT;
        file_put_contents('/tmp/song.txt', file_put_contents('/tmp/song.txt', $song));

        return $handler->handle($request);
    }
}