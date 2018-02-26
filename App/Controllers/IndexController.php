<?php


namespace App\Controllers;


use Core\Container\Container;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController
{
    private $twig;

    public function __construct()
    {
        $twig = Container::getContainer()->get('twig');
        $this->twig = $twig;
    }

    public function __invoke()
    {
        $template = $this->twig->load('index.twig')->render([
            'title' => 'Main Page'
        ]);
        return new HtmlResponse($template);
    }


}