<?php


namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class MiddlewareOne
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('one', 1));
    }
}