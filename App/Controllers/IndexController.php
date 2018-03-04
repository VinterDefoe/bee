<?php


namespace App\Controllers;


use Core\Container\Container;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController
{
    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    public function __construct()
    {
        $twig = Container::getContainer()->get('twig');
        $this->twig = $twig;
    }

    public function __invoke()
    {
        $template = $this->twig->load('index.twig');
        $template = $template->render([
            'title' => 'Main Page',
            'user' => true
        ]);

        return new HtmlResponse($template);
    }


}