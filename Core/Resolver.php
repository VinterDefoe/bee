<?php


namespace Core;


use Core\Middleware\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

class Resolver
{
    public function resolver($handler)
    {
        if(\is_string($handler)){
            return function (ServerRequestInterface $request,callable $next) use($handler){
              $obj = new $handler();
              return $obj($request,$next);
            };
        }
        if(is_array($handler)){
            return $this->createPipe($handler);
        }
        if(is_callable($handler)){
            return $handler;
        }
        throw new \InvalidArgumentException();
    }

    private function createPipe(array $handlers)
    {
        $pipeline = new Pipeline();

        foreach ($handlers as $handler){
                $pipeline->pipe($this->resolver($handler));
        }
        return $pipeline;
    }
}