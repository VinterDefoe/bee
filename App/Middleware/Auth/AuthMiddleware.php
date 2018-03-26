<?php


namespace App\Middleware\Auth;


use App\Models\Users;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    const USER = 'user';

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if (isset($request->getCookieParams()['token'])) {
            $token = explode(":", $request->getCookieParams()['token']);
            $model = new Users();
            if ($user = $model->userIdentification($token[1], $token[0])) {
                unset($user['user_password']);
                return $next($request->withAttribute(self::USER, $user));
            }
        }
        return $next($request);
    }
}