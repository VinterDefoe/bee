<?php


namespace App\Middleware\Auth;


use App\Models\Users;
use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    const USER = 'user';


    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if(isset($request->getCookieParams()['token'])){
            $token = explode(":",$request->getCookieParams()['token']);
            $model = new Users();
            if($user = $model->userIdentifi($token[1],$token[0])){
                unset($user['user_password']);
                return $next($request->withAttribute(self::USER,$user));
            }
        }
        return $next($request);
    }
}