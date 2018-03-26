<?php


namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CatcherErrorMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                [$exception->getMessage()],
                [$exception->getLine()],
                [$exception->getFile()]
            ]);
        }
    }
}