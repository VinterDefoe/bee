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
        if (!\in_array($request->getMethod(), $this->method)) return false;

        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
        }, $this->pattern);
        if (preg_match('~^'.$pattern.'$~i', $request->getUri()->getPath(), $match)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($match, '\is_string', ARRAY_FILTER_USE_KEY));
        }
        return false;
    }
}
