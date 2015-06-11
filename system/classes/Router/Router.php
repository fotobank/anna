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
	 * @param $site_routes array Array list of routes from routes config file.
	 */
	public function start($site_routes)
		{
//			$http_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/';
//			$url = cp1251(urldecode(parse_url($http_url, PHP_URL_PATH)));
			$url = isset($_GET['url'])?$_GET['url']:'/';
			$routes = array_values(array_filter(explode('/', $url)));
//			$request = implode('/', $routes);

			if (array_key_exists($url, $site_routes)) {
				foreach ($site_routes as $key => $value) {
					if ($key == $url) {
						$controller = $value['controller'];
						$method = $value['method'];
						$this->prepareParams($routes);
						$this->prepareRoute($controller, $method);
					}
				}
			} elseif (is_file(SITE_PATH . $url)) {

				// если изображение - отдать
				$ext = pathinfo($url, PATHINFO_EXTENSION);
				if(in_array($ext, ['jpg', 'png', 'gif'])) {

					echo $url;

				} else {
					$controller = 'controllers' . DS . 'Error' . DS . 'Error';
					$method = 'error404';
					$this->prepareRoute($controller, $method);
				}

			} else {
				$controller = 'controllers' . DS . 'Error' . DS . 'Error';
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
			$controller_path = SITE_PATH . 'system' . DS . $controller . '.php';
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
					throw new Exception('контроллер "' . $controller_path . '" не найден');
				}
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					die('Ошибка: ' . $e->getMessage() . '<br>');
				}
				header('Location: /404.php', '', 404);;
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
					header('Location: /404.php', '', 404);
				}
			} else {
				header('Location: /404.php', '', 404);
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