<?php


namespace App\Controllers;


use Zend\Diactoros\Response\JsonResponse;

class LoginController
{
    public function __invoke()
    {
        return new JsonResponse([1]);
    }
}