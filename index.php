<?php

use App\Controllers\BlogController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Middleware\Auth\AuthMiddleware;
use App\Middleware\Auth\LoginMiddleware;
use App\Middleware\CatcherErrorMiddleware;
use App\Middleware\NotFoundPageMiddleware;
use App\Middleware\TimerMiddleware;
use Core\Application;
use Core\Container\Container;
use Core\Middleware\MiddlewareResolver;
use Core\Middleware\RouterMiddleware;
use Core\Router\RouteCollections;
use Core\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;


require_once "vendor/autoload.php";

#Init
$db = new PDO('sqlite:Db/app.db');
$request = ServerRequestFactory::fromGlobals();
$resolver = new MiddlewareResolver();
$emitter = new SapiEmitter();
$app = new Application($resolver, new NotFoundPageMiddleware());
$routeCollections = new RouteCollections();
## Container

$container = Container::getContainer();
$container->add('db', $db);
$container->add('templatePath', 'App/Views');

#Routing

$routeCollections->post('login', '^/login/', LoginController::class);
$routeCollections->get('blog', '^/blog/{id}', BlogController::class, ['id' => '\d+']);
$routeCollections->get('index', '^/{id}', IndexController::class, ['id' => '\d+']);
$routeCollections->get('index_list', '^/', IndexController::class);

$router = new Router($routeCollections);

$app->pipe(CatcherErrorMiddleware::class);
$app->pipe(TimerMiddleware::class);
$app->pipe(AuthMiddleware::class);
$app->pipe(new RouterMiddleware($router, $resolver));

/**
 * @var ResponseInterface $response
 */
$response = $app->run($request);

# Sending

$emitter->emit($response);





