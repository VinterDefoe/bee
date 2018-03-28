<?php


namespace App\Controllers\Admin;


use App\Helpers\UserHelper;
use App\Models\Reviews;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class ReviewController extends TwigView
{
    use UserHelper;

    /**
     * @param ServerRequestInterface $request
     * @param $next
     * @return HtmlResponse|RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(ServerRequestInterface $request, $next)
    {
        $model = new Reviews();
        $error = [];
        if ($request->getMethod() === "POST") {
            $model->loadDate($request);
            $error = $model->hasValidationError();
            if (!$error) {
                $model->changeReview();
                return new RedirectResponse('/admin/');
            }
        }
        $id = $request->getAttribute('id');
        $review = $model->getReview($id);
        if(!$review) return $next($request);
        $userAttr = $this->getUserAttributes($request);
        $template = $this->twig->load('/Admin/review.twig');
        $attributes = [
            'review' => $review,
            'title' => $review['review_name'],
            'user' => $userAttr,
            'error' => $error
        ];
        $template = $template->render($attributes);
        return new HtmlResponse($template);
    }
}