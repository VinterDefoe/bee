<?php

use App\Controllers\IndexController;
use Core\Resolver;
use Core\Router\Result;
use Core\Router\Router;
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

$router->get('index','#^/$#',new IndexController($twig));

#Actions

/**
 * @var Result $result
 */
$result = $router->match($request);

$action = $resolver->resolver($result->getHandler());

$response = $action($request);


#Sending

$emitter->emit($response);







