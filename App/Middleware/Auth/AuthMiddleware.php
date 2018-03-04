<?php


namespace App\Middleware\Auth;


use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    const USER= 'user';

    private $twig;

    public function __construct()
    {
        $twig = Container::getContainer()->get('twig');
        $this->twig = $twig;
    }


    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if(isset($request->getCookieParams()['token'])){
           $token =  $request->getCookieParams()['token'];
           $user = ['userName'=>'admin','role' => 1];
            $this->twig->load('index.twig')->render([
                'title' => 'Main Page'
            ]);
           return $next($request->withAttribute(self::USER,$user));
        }
        return $next($request);
    }
}