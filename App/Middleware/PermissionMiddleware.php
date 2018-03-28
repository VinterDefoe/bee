<?php


namespace App\Middleware;


use App\Helpers\UserHelper;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class PermissionMiddleware
{
    use UserHelper;

    public function __invoke(ServerRequest $request, callable $next)
    {
        $user = $this->getUserAttributes($request);
        if($user){
            if($user['userRole'] < 10){
                return $next($request);
            }
        }
        return new RedirectResponse('/');
    }
}