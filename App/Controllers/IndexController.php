<?php


namespace App\Controllers;


use Zend\Diactoros\Response\HtmlResponse;

class IndexController
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {

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