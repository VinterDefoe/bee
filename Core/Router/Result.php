<?php


namespace Core\Router;


class Result
{
    private $name;
    private $handler;

    /**
     * Result constructor.
     * @param $name
     * @param $handler
     */
    public function __construct($name, $handler)
    {
        $this->name = $name;
        $this->handler = $handler;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }
}