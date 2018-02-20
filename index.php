<?php

use Core\Router\Router;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require_once "vendor/autoload.php";

#Init

$request = ServerRequestFactory::fromGlobals();
$router = new Router();
$emitter = new SapiEmitter();

#Route



#Actions

#Sending








