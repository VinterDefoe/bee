<?php


namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TimerMiddleware
{

    public function __invoke(ServerRequestInterface $request,  callable $next)
    {

        $start = microtime(true);

        $response =  $next($request);

        $stop = microtime(true);
        /**
         * @var ResponseInterface $response
         */
        return $response->withHeader('X-Timer',$stop-$start);


    }
}