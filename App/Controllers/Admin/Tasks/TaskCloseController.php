<?php


namespace App\Controllers\Admin\Tasks;


use App\Models\Tasks;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class TaskCloseController
{
	public function __invoke(ServerRequestInterface $request, callable $next)
	{
		$id = $request->getAttribute('id');
		if($id){
			$model = new Tasks();
			$model->changeStatus($id, 2);
			return new RedirectResponse('/admin/tasks/');
		}
		return $next($request);
	}
}