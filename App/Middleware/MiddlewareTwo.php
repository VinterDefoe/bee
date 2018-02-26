<?php


namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class MiddlewareTwo
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('two',2));
    }}