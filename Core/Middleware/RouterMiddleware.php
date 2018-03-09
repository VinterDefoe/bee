<?php


namespace Core\Middleware;


use Core\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware
{

    private $router;
    private $resolver;

    public function __construct(Router $router,MiddlewareResolver $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $result = $this->router->match($request);
        if(!$result) return $next($request);

        foreach ($result->getAttributes() as $name=>$value){
            $request = $request->withAttribute($name,$value);
        }
        $handler = $this->resolver->resolve($result->getHandler());
        return $handler($request,$next);
    }
}