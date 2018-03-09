<?php


namespace Core\Views;


use Core\Container\Container;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigView
{
    private $templatePath;
    private $defaultTemplatePath = 'App/Views';
    /**
     * @var Twig_Environment
     */
    protected $twig;

    public function __construct()
    {
        if($path = Container::getContainer()->get('templatePath')){
            $this->templatePath = $path;
        }else{
            $this->templatePath = $this->defaultTemplatePath;
        }
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem($this->templatePath));
    }

}