<?php


namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class LoginController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];
        return new JsonResponse(['Login'=>$login,'Password'=> $password]);
    }
}