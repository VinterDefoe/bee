<?php


namespace Core\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class Pipeline
{
    private $pipes;

    public function __construct()
    {
        $this->pipes = new \SplQueue();
    }

    public function pipe($handler)
    {
        $this->pipes->enqueue($handler);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->next($request);
    }

    public function next(ServerRequestInterface $request)
    {
        if($this->pipes->isEmpty()) return new Response('Page Not Found',404);

        $pipe = $this->pipes->dequeue();


        return $pipe($request,function (ServerRequestInterface $request){
           return $this->next($request);
        });
    }

    public function getPipe()
    {
        return $this->pipes;
    }

}