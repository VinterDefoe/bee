<?php


namespace App\Controllers;


use Zend\Diactoros\Response\JsonResponse;

class BlogController
{
    public function __invoke()
    {
        return new JsonResponse(['name' => 'John']);
    }
}