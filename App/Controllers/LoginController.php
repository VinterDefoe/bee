<?php


namespace App\Controllers;


use App\Models\Users;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

class LoginController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];

        $model = new Users();
        if($user = $model->userIdentifi($login,md5($password))){
            $token = md5($password).':'.$login;
            setcookie('token',$token,time()+360,'/');
            return new RedirectResponse('/');
        }
        return new JsonResponse(['Login'=>$login,'Password'=> $password]);
    }
}