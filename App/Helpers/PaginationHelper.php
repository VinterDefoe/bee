<?php


namespace App\Helpers;


use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

trait PaginationHelper
{
	/**
	 * @param $currentPage
	 * @param $maxPerPage
	 * @param $data
	 * @param callable $route
	 * @return mixed
	 */
	public function pagination($currentPage,$maxPerPage,$data, callable $route)
	{
		$adapter = new ArrayAdapter($data);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta
			->setMaxPerPage($maxPerPage)
			->setCurrentPage($currentPage);
		$view = new TwitterBootstrap4View();
		return $view->render($pagerfanta, $route);
	}
}