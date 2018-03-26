<?php


namespace App\Controllers;


use App\Helpers\UserHelper;
use App\Models\Reviews;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class IndexController extends TwigView
{
    use UserHelper;

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse|RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $model = new Reviews();
        $error = [];
        if ($request->getMethod() === "POST") {
            $model->loadDate($request);
            $error = $model->validate();
            if (!$error) {
                $model->addReview();
                return new RedirectResponse('/');
            }
        }
        $reviews = $model->read();
        $template = $this->twig->load('index.twig');
        $userAttr = $this->getUserAttributes($request);
        $attributes = [
            'reviews' => $reviews,
            'title' => 'Main Page',
            'error' => $error,
            'user' => $userAttr
        ];
        $template = $template->render($attributes);
        return new HtmlResponse($template);
    }
}