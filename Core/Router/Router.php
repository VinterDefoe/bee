<?php



namespace Core\Router;


use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private $routes = [];

    public function __construct()
    {

    }


    public function match(ServerRequestInterface $request)
    {
        foreach ($this->routes as $route){
            /**
             * @var Route $route
             */
            $result = $route->match($request);
            if($result) return $result;
        }
        throw new \LogicException("Can't match route");
    }
}