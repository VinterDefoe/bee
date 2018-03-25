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
        if ($request->getMethod() === "POST"){
           if($erorr = $model->validate($request)){
               var_dump($erorr);
           }
        }
        $reviews = $model->read();
//        $model->create('John','John@email.ru','review from John','upload/man.jpg');
//        $model->create('Neo','Neo@email.ru','review from Neo','upload/matrix.jpg');
//        $model->create('Alex','Alex@email.ru','review from Alex','upload/img1.jpg');

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