<?php


namespace Core\Router;


use Psr\Http\Message\ServerRequestInterface;

class Route
{
    /**
     * @var array
     */
    private $method;
    private $name;
    private $pattern;
    private $handler;
    /**
     * @var array
     */
    private $tokens;


    /**
     * Route constructor.
     * @param array $method
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     */
    public function __construct($method = [], $name, $pattern, $handler, $tokens = [])
    {
        $this->method = $method;
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->tokens = $tokens;
    }

    public function match(ServerRequestInterface $request)
    {
        if(!\in_array($request->getMethod(),$this->method)) return false;

        if($match = preg_match($this->pattern,$request->getUri()->getPath(),$match)){
            return new Result($this->name,$this->handler);
        }
        return false;
    }
}
