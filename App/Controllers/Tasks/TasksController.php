<?php


namespace App\Controllers\Tasks;


use App\Helpers\UserHelper;
use App\Models\Tasks;
use Core\Views\TwigView;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class TasksController extends TwigView
{
	use UserHelper;

	public function __invoke(ServerRequestInterface $request)
	{
		$model = new Tasks();
		$error = [];
		if ($request->getMethod() === "POST") {
			$model->loadData($request);
			$error = $model->hasValidationError();
			if (!$error) {
				$model->addTask();
				return new RedirectResponse('/');
			}
		}
		$tasks = $model->read();
		$template = $this->twig->load('/Tasks/Tasks.twig');
		$userAttr = $this->getUserAttributes($request);
		$attributes = [
			'tasks' => $tasks,
			'title' => 'Main Page',
			'error' => $error,
			'user' => $userAttr
		];
		$template = $template->render($attributes);
		return new HtmlResponse($template);
	}
}