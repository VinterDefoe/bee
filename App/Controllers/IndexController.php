<?php


namespace App\Controllers;


use App\Middleware\Auth\AuthMiddleware;
use App\Models\Reviews;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController extends TwigView
{

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

        $template = $this->twig->load('index.twig');
        $userAttr = $this->getUserAttributes($request);
        $attributes = [
            'reviews' => $reviews,
            'title' => 'Main Page'
        ];
        $attributes = array_merge($userAttr, $attributes);

        $template = $template->render($attributes);


        return new HtmlResponse($template);
    }

    private function getUserAttributes(ServerRequestInterface $request)
    {
        if ($user = $request->getAttribute(AuthMiddleware::USER)) {
            return [
                'userName' => $user['user_name'],
                'userRole' => $user['user_role']
            ];
        }
        return [];
    }
}