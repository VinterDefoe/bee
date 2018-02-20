<?php



namespace Core\Router;


use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private $routes = [];


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

    public function add($method = [],$name,$pattern,$handler,$token = [])
    {
        $this->routes[] = new Route($method,$name,$pattern,$handler,$token);
    }

    public function get($name,$pattern,$handler,$token = [])
    {
        $this->add(['GET'],$name,$pattern,$handler,$token);
    }

    public function post($name,$pattern,$handler,$token = [])
    {
        $this->add(['POST'],$name,$pattern,$handler,$token);
    }
}