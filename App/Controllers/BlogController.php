<?php


namespace App\Controllers;


use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class BlogController
{
    private $twig;

    public function __construct()
    {
        $twig = Container::getContainer()->get('twig');
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        var_dump($request);
        $id = $request->getAttribute('id');

        return new JsonResponse(['id' => $id]);
    }
}