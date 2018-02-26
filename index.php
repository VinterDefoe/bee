<?php

use App\Controllers\IndexController;
use App\Middleware\MiddlewareOne;
use App\Middleware\MiddlewareTwo;
use App\Middleware\NotFoundPageMiddleware;
use App\Middleware\TimerMiddleware;
use Core\Container\Container;
use Core\Middleware\Resolver;
use Core\Pipeline\Pipeline;
use Core\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;


require_once "vendor/autoload.php";


#Init

$request = ServerRequestFactory::fromGlobals();
$router = new Router();
$resolver = new Resolver();
$emitter = new SapiEmitter();
$pipeline = new Pipeline();

#Twig

$loader = new Twig_Loader_Filesystem('App/Views');
$twig = new Twig_Environment($loader);
## Container

$container = Container::getContainer();
$container->add('twig', $twig);


$pipeline->pipe($resolver->resolve([
    TimerMiddleware::class,
    MiddlewareOne::class,
    MiddlewareTwo::class
]));
$pipeline->pipe($resolver->resolve(MiddlewareOne::class));
$pipeline->pipe($resolver->resolve(MiddlewareTwo::class));
$pipeline->pipe($resolver->resolve(IndexController::class));

/**
 * @var ResponseInterface $response
 */
$response = $pipeline($request, new NotFoundPageMiddleware());

$res = new MiddlewareOne();
echo $res->run();

# Sending

//$emitter->emit($response);





