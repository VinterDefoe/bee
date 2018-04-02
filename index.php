<?php

use App\Controllers\Admin\Tasks\TaskCloseController;
use App\Controllers\Admin\Tasks\TaskController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\Tasks\TasksController;
use App\Middleware\Auth\AuthMiddleware;
use App\Middleware\CatcherErrorMiddleware;
use App\Middleware\NotFoundPageMiddleware;
use App\Middleware\PermissionMiddleware;
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
$routeCollections->get('task_close', '/admin/task/close/{id}', [PermissionMiddleware::class, TaskCloseController::class], ['id' => '\d+']);
$routeCollections->add(['GET', 'POST'], 'admin_task', '/admin/task/{id}', [PermissionMiddleware::class, TaskController::class], ['id' => '\d+']);
$routeCollections->get('admin_tasks', '/admin/tasks/{page}?', [PermissionMiddleware::class, \App\Controllers\Admin\Tasks\TasksController::class], ['page' => '\d+']);
$routeCollections->add(['GET', 'POST'], 'login', '/login/', LoginController::class);
$routeCollections->get('logout', '/logout/', LogoutController::class);
$routeCollections->add(['GET', 'POST'], 'tasks_sort', '/{page}\\?{sort}?', TasksController::class, ['page' => '\d+']);
$routeCollections->add(['GET', 'POST'], 'tasks', '/{page}?', TasksController::class);

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




