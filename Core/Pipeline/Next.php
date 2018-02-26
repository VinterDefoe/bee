<?php


namespace Core\Pipeline;


use Psr\Http\Message\ServerRequestInterface;

class Next
{

    private $defaultPipe;
    private $pipes;

    public function __construct(callable $defaultPipe,\SplQueue $pipes)
    {
        $this->defaultPipe = $defaultPipe;
        $this->pipes = $pipes;
    }

    public function next(ServerRequestInterface $request)
    {
        if($this->pipes->isEmpty()){
            return ($this->defaultPipe)($request);
        }
        $pipe = $this->pipes->dequeue();

        return $pipe($request,function (ServerRequestInterface $request){
            return $this->next($request);
        });
    }
}