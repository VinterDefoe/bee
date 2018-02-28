<?php


namespace Core\Middleware;


use Core\Pipeline\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareResolver
{
    public function resolve($handler)
    {
        if(\is_string($handler)){
            return function (ServerRequestInterface $request,callable $next) use ($handler){
              $obj = new $handler();
              return $obj($request,$next);
            };
        }
        if(\is_array($handler)){
            return $this->createPipe($handler);
        }
        return $handler;
    }

    private function createPipe($handler)
    {
        $pipeline = new Pipeline();
        foreach ($handler as $item){
            $pipeline->pipe($this->resolve($item)) ;
        }
        return $pipeline;
    }
}