<?php


namespace App\Controllers\Admin;


use App\Helpers\UserHelper;
use App\Models\Reviews;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController extends TwigView
{
    use UserHelper;

    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Reviews();
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if($id = $request->getAttribute('id')){
           return $this->renderReview($id,$request);
        }
        $model = $this->model;
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

    /**
     * @param $id
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function renderReview($id,ServerRequestInterface $request)
    {
        $model = $this->model;
        $review = $model->getReview($id);
        $userAttr = $this->getUserAttributes($request);
        $template = $this->twig->load('/Admin/review.twig');
        $attributes = [
            'review' => $review,
            'title' => $review['review_name'],
            'user' => $userAttr
        ];
        $template = $template->render($attributes);
        return new HtmlResponse($template);
    }
}