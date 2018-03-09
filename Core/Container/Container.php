<?php


namespace Core\Container;


class Container
{
    private static $instance;

    private $container = [];

    private function __construct()
    {

    }

    public static function getContainer() : Container
    {
        if(!isset(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    public function add($name,$value)
    {
        $this->container[$name] = $value;
    }

    public function get($name)
    {
        if(!isset($this->container[$name])) return false;
        return $this->container[$name];
    }

}