<?php


namespace Core\Pipeline;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class Pipeline
{
    private $pipes;

    public function __construct()
    {
        $this->pipes = new \SplQueue();
    }

    public function __invoke(ServerRequestInterface $request,callable $default)
    {
        $next = new Next($default, $this->pipes);
        return $next->next($request);
    }


    public function pipe($handler)
    {
        $this->pipes->enqueue($handler);
    }

    /**
     * @return \SplQueue
     */
    public function getPipes(): \SplQueue
    {
        return $this->pipes;
    }
}