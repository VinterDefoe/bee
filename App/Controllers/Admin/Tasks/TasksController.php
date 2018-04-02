<?php


namespace App\Controllers\Admin\Tasks;

use App\Helpers\PaginationHelper;
use App\Helpers\UserHelper;
use App\Models\Tasks;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class TasksController extends TwigView
{

	use UserHelper;
	use PaginationHelper;

	/**
	 * @param ServerRequestInterface $request
	 * @param callable $next
	 * @return HtmlResponse|RedirectResponse
	 */
	public function __invoke(ServerRequestInterface $request,callable $next)
	{
		$model = new Tasks();
		$sort = $request->getQueryParams()['sort'] ?? false;
		$page = $request->getAttribute('page');
		if(!$page) {$page = 1;}
		$tasks = $model->getTask($page, 3, $sort);
		if(!$tasks){return $next($request);}
		$pagination = $this->pagination($page, 3, $model->dataForPagination(), function ($page) use ($sort) {
			return ($sort) ? '/admin/tasks/' . $page . '?sort=' . $sort : '/admin/tasks/' . $page;
		});
		$template = $this->twig->load('Admin/Tasks/Tasks.twig');
		$userAttr = $this->getUserAttributes($request);
		$attributes = [
			'tasks' => $tasks,
			'title' => 'Admin Page',
			'user' => $userAttr,
			'pagination' => $pagination
		];
		$template = $template->render($attributes);
		return new HtmlResponse($template);
	}
}