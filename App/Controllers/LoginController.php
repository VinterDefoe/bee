<?php


namespace App\Controllers;


use App\Models\Users;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class LoginController
{
    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse|RedirectResponse
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === "POST") {
            $login = $request->getParsedBody()['login'];
            $password = $request->getParsedBody()['password'];

            $model = new Users();
            if ($user = $model->userIdentification($login, md5($password))) {
                $token = md5($password) . ':' . $login;
                setcookie('token', $token, time() + 360000, '/');
                return new RedirectResponse('/');
            }
            return new HtmlResponse('Not correct Login or Password');
        }

        return new HtmlResponse('Login page');
    }
}