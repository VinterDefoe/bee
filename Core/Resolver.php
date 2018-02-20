<?php


namespace Core;


class Resolver
{
    public function resolver($handler)
    {
       if(\is_string($handler)){
           return new $handler();
       }
       if(\is_callable($handler)){
           return $handler;
       }
       if(\is_array($handler)){

       }
       throw new \LogicException('Wrong type $handler');
    }
}