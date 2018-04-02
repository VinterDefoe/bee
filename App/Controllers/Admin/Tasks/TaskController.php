<?php


namespace App\Controllers\Admin\Tasks;


use App\Helpers\UserHelper;
use App\Models\Tasks;
use Core\Views\TwigView;
use Pagerfanta\Exception\LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class TaskController extends TwigView
{
	use UserHelper;

	/**
	 * @param ServerRequestInterface $request
	 * @param callable $next
	 * @return HtmlResponse|RedirectResponse
	 */
	public function __invoke(ServerRequestInterface $request, callable $next)
	{
		$model = new Tasks();
		if ($request->getMethod() === "POST") {
			$text = $request->getParsedBody()['task'] ?? false;
			$id = $request->getParsedBody()['id'] ?? false;
			if($id && $text){
				$res = $model->changeTaskText($id,$text);
				if(!$res) throw new LogicException('Undefined error');
				return new RedirectResponse('/admin/tasks/');
			}
		}
		$id = $request->getAttribute('id');
		$task = $model->getTaskText($id);
		if(!$task) return $next($request);
		$userAttr = $this->getUserAttributes($request);
		$template = $this->twig->load('Admin/Tasks/Task.twig');
		$attributes = [
			'task' => $task,
			'title' => $task['task_name'],
			'user' => $userAttr,
		];
		$template = $template->render($attributes);
		return new HtmlResponse($template);
	}
}