<?php


namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

class LoginController
{

    /**
     * @var array
     */
    private $users;

    public function __construct(array $users)
    {

        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];

        foreach ($this->users as $user){
            if($login == $user['login'] && $password == $user['password']){
                $hash = md5($login.$password);
                setcookie('token',$hash,time()+360);
                return new RedirectResponse('/');
            }
        }
        return new JsonResponse(['Login'=>$login,'Password'=> $password]);
    }
}