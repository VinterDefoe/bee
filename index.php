<?php

use App\Controllers\IndexController;
use App\Middleware\NotFoundPageMiddleware;
use App\Middleware\TimerMiddleware;
use Core\Container\Container;
use Core\Middleware\Pipeline;
use Core\Resolver;
use Core\Router\Result;
use Core\Router\Router;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require_once "vendor/autoload.php";

#Init

$request = ServerRequestFactory::fromGlobals();
$router = new Router();
$resolver = new Resolver();
$emitter = new SapiEmitter();


#Twig

$loader = new Twig_Loader_Filesystem('App/Views');
$twig = new Twig_Environment($loader);
## Container

$container = Container::getContainer();
$container->add('twig', $twig);


//$router->get('index','#^/$#',IndexController::class);
//$router->get('index','#^/$#',function (ServerRequestInterface $request) use($twig,$pipeline){
//    $pipeline->pipe(new TimerMiddleware());
//    $pipeline->pipe(new IndexController($twig));
//    $pipeline->pipe(new NotFoundPageMiddleware());
//    return $pipeline($request);
//});

$router->get('index', '#^/$#', [
    TimerMiddleware::class,
    IndexController::class,
]);

$pipeline = new Pipeline();
#Actions


/**
 * @var Result $result
 */
$result = $router->match($request);
$pipeline->pipe($resolver->resolver($result->getHandler())) ;
$pipeline->pipe($resolver->resolver(1)) ;

try{
    $response = $pipeline($request);
}catch (\Throwable $e){
    echo $e->getMessage();
}








#Sending

$emitter->emit($response);







