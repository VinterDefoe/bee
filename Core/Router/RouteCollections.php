<?php


namespace Core\Router;


class RouteCollections
{
    public $routes = [];

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