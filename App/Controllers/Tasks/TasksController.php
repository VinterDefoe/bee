<?php


namespace App\Controllers\Tasks;


use App\Helpers\PaginationHelper;
use App\Helpers\UserHelper;
use App\Models\Tasks;
use Core\Views\TwigView;
use LogicException;
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
		$error = [];
		if ($request->getMethod() === "POST") {
			$model->loadData($request);
			$error = $model->hasValidationError();
			if (!$error) {
				$res = $model->addTask();
				if(!$res) throw new LogicException('Undefined error');
				return new RedirectResponse('/');
			}
		}
		$sort = $request->getQueryParams()['sort'] ?? false;
		$page = $request->getAttribute('page');
		if(!$page) {$page = 1;}
		$tasks = $model->getTask($page, 3, $sort);
//		/if(!$tasks){return $next($request);}
		$pagination = $this->pagination($page, 3, $model->dataForPagination(), function ($page) use ($sort) {
			return ($sort) ? '/' . $page . '?sort=' . $sort : '/' . $page;
		});
		$template = $this->twig->load('/Tasks/Tasks.twig');
		$userAttr = $this->getUserAttributes($request);
		$attributes = [
			'tasks' => $tasks,
			'title' => 'Main Page',
			'error' => $error,
			'user' => $userAttr,
			'pagination' => $pagination,
		];
		$template = $template->render($attributes);
		return new HtmlResponse($template);
	}
}