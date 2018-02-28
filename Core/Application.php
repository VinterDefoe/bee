<?php


namespace Core;


use Core\Middleware\MiddlewareResolver;
use Core\Pipeline\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    /**
     * @var MiddlewareResolver
     */
    private $resolver;
    /**
     * @var callable
     */
    private $defaultPipe;

    public function __construct(MiddlewareResolver $resolver,callable $defaultPipe)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->defaultPipe = $defaultPipe;
    }

    /**
     * @param $handler
     */
    public function pipe($handler)
    {
        parent::pipe($this->resolver->resolve($handler));
    }

    public function run(ServerRequestInterface $request)
    {
        return $this($request,$this->defaultPipe);
    }

}
