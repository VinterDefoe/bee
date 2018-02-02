<?php

use Core\Http\Request;

require_once "vendor/autoload.php";

$request = new Request();
$request=$request->withQueryParams();


var_dump($request);