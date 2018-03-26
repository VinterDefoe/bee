<?php


namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class LogoutController
{
    public function __invoke(ServerRequestInterface $request)
    {
        if(isset($request->getCookieParams()['token'])){
          $token = $request->getCookieParams()['token'];
          setcookie('token', $token, time() - 360000, '/');
        }
        return new RedirectResponse('/');
    }
}