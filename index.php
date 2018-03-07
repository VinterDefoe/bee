<?php

use App\Controllers\BlogController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Middleware\CatcherErrorMiddleware;
use App\Middleware\NotFoundPageMiddleware;
use App\Middleware\TimerMiddleware;
use Core\Application;
use Core\Container\Container;
use Core\Middleware\MiddlewareResolver;
use Core\Router\RouteCollections;
use Core\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;


require_once "vendor/autoload.php";

#config

$config = [
    'users' => [
        ['login' => 'admin','password'=>'123'],
        ['login' => 'user','password'=>'user'],
                ]
];

#Init
$request = ServerRequestFactory::fromGlobals();
$resolver = new MiddlewareResolver();
$emitter = new SapiEmitter();
$app = new Application($resolver, new NotFoundPageMiddleware());
$routeCollections = new RouteCollections();

#Twig

$loader = new Twig_Loader_Filesystem('App/Views');
$twig = new Twig_Environment($loader);
## Container

$container = Container::getContainer();
$container->add('twig', $twig);

#Routing

$routeCollections->post('login','^/login/',new LoginController($config['users']));
$routeCollections->get('blog','^/blog/{id}',BlogController::class,['id'=>'\d+']);
$routeCollections->get('index','^/{id}',IndexController::class,['id'=>'\d+']);
$routeCollections->get('index_list','^/',IndexController::class);

$router = new Router($routeCollections);

$app->pipe(CatcherErrorMiddleware::class);
$app->pipe(TimerMiddleware::class);

$result = $router->match($request);
foreach ($result->getAttributes() as $name=>$value){
    $request = $request->withAttribute($name,$value);

}
$app->pipe($result->getHandler());

//setcookie('test','test',time()+3600);
//var_dump($request);
$db = new SQLite3('mysqlitedb.db');
/**
 * @var ResponseInterface $response
 */
$response = $app->run($request);


# Sending

$emitter->emit($response);





