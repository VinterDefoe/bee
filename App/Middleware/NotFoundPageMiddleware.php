<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class NotFoundPageMiddleware
{
    public function __invoke(ServerRequestInterface $request,callable $next)
    {
        return new Response\HtmlResponse('Not Found',404);
    }
}