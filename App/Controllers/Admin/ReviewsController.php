<?php


namespace App\Controllers\Admin;


use App\Helpers\UserHelper;
use App\Models\Reviews;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ReviewsController extends TwigView
{
    use UserHelper;

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $model = new Reviews();
        $reviews = $model->read();
        $template = $this->twig->load('/Admin/index.twig');
        $userAttr = $this->getUserAttributes($request);
        $attributes = [
            'reviews' => $reviews,
            'title' => 'Admin Page',
            'user' => $userAttr
        ];
        $template = $template->render($attributes);
        return new HtmlResponse($template);
    }

}