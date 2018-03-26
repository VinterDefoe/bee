<?php

namespace App\Helpers;


use App\Middleware\Auth\AuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;

trait UserHelper
{
    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getUserAttributes(ServerRequestInterface $request)
    {
        if ($user = $request->getAttribute(AuthMiddleware::USER)) {
            return [
                'userName' => $user['user_name'],
                'userRole' => $user['user_role']
            ];
        }
        return [];
    }
}