<?php


namespace App\Controllers\Admin;


use App\Models\Reviews;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class DeclineReviewController
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $id = $request->getAttribute('id');
        if($id){
            $model = new Reviews();
            $model->changeStatus($id, 11);
            return new RedirectResponse('/admin/');
        }
        return $next($request);
    }
}