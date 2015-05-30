<?php

/**
 * Класс Router
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     1:15
 * @license   MIT License: http://opensource.org/licenses/MIT
 */
class Router
{

	/**
	 * @var $param string Param that sets after request url checked.
	 */
	private $param;
	/**
	 * @var $id
	 *  that sets after request url checked.
	 */
	private $id;


	/**
	 * Mapping requested URL with specified routes in routing list.
	 *
	 * @param $leo_routes array Array list of routes from routes config file.
	 */
	public function start($leo_routes)
		{
			$step = explode('?', $_SERVER['REQUEST_URI']);
			$routes = explode('/', $step[0]);
			$routes_1 = isset($routes[1]) ? $routes[1] : '';
			$routes_2 = isset($routes[2]) ? $routes[2] : '';
			if ($routes_1 != '' && $routes_2 == '') {
				$request = $routes_1;
			} else {
				$request = $routes_1 . '/' . $routes_2;
			}

			if (array_key_exists($request, $leo_routes)) {
				foreach ($leo_routes as $key => $value) {
					if ($key == $request) {
						$controller = $value['controller'];
						$method = $value['method'];
						$this->prepareParams($routes);
						$this->prepareRoute($controller, $method);
					}
				}
			} else {
				$controller = 'error';
				$method = 'error404';
				$this->prepareRoute($controller, $method);
			}
		}


	/**
	 * Preparing controllerto be included. Checking is controller exists.
	 * Creating new specific model instance. Creating controller instance.
	 *
	 * @param $controller string Controller name.
	 * @param $method     string Method name.
	 */
	protected function prepareRoute($controller, $method)
		{
			$controller_path = SITE_PATH . 'system' . DS . 'controllers' . DS . $controller . DS . $controller . '.php';
			$this->checkControllerExists($controller_path);
			$this->createModelInstance($controller);
			$this->createInstance($controller, $method);
		}

	/**
	 * Checks requested URL on params and id and if exists sets to the private vars.
	 *
	 * @param $routes array Requested URL.
	 */
	protected function prepareParams($routes)
		{
			if ((!empty($routes[3]) && !empty($routes[4])) || !empty($routes[3]) || !empty($routes[4])) {
				$this->id = $routes[4];
				$this->param = $routes[3];
			}
		}

	/**
	 * Checks is controller exists and inlcude it.
	 *
	 * @param $controller_path string Controller path. Used to include and controller.
	 *
	 * @throws Exception
	 */
	protected function checkControllerExists($controller_path)
		{
			try {
				if (file_exists($controller_path)) {
					/** @noinspection PhpIncludeInspection */
					require_once $controller_path;
				} else {
					throw new Exception;
				}
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}
		}

	/**
	 * Creating new instance that required by URL.
	 *
	 * @param $controller string Controller name.
	 * @param $method     string Method name.
	 */
	protected function createInstance($controller, $method)
		{
			$instance = new $controller;

			if (method_exists($instance, $method)) {
				$reflection = new ReflectionMethod($instance, $method);
				if ($reflection->isPublic()) {
					$instance->$method($this->param, $this->id);
				} else {
					header('Location: error404');
				}
			} else {
				header('Location: error404');
			}
		}

	/**
	 * Creates instance of model by requested controller.
	 *
	 * @param $controller string Controller name.
	 */
	protected function createModelInstance($controller)
		{

			$model = SITE_PATH . 'system' . DS . 'models' . DS . $controller . DS . $controller . '.php';

			if (file_exists($model)) {
				/** @noinspection PhpIncludeInspection */
				require_once($model);
			}
		}
}