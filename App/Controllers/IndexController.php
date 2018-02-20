<?php


namespace App\Controllers;


use Zend\Diactoros\Response\JsonResponse;

class IndexController
{
    public function __invoke()
    {
        return new JsonResponse(['id'=>5]);
    }
}