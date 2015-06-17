<?php

/**
 * ����� Router
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
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
	/** @var mixed */
	protected $site_routes;

	/**
	 *
	 */
	public function __construct()
		{
			/** @noinspection PhpIncludeInspection */
			$this->site_routes = include(SITE_PATH . 'system/classes/Router/routes.php');
		}

	/**
	 * @param $route
	 *
	 * @throws routeException
	 */
	public function set_route($route)
		{
			if (is_array($route)) {
				$this->site_routes = array_merge($this->site_routes, $route);
			} else {
				throw new routeException('$route is not array');
			}
		}

	/**
	 * Mapping requested URL with specified routes in routing list.
	 *
	 * @internal param array $site_routes Array list of routes from routes config file.
	 */
	public function start()
		{
			try {
				$url = array_key_exists('url', $_GET) ? $_GET['url'] : '/';
				$routes = array_values(array_filter(explode('/', $url)));
				$array_key = array_key_exists(0, $routes) ? $routes[0] : '/';

				if (array_key_exists($array_key, $this->site_routes)) {

					foreach ($this->site_routes as $key => $value) {
						if ($key === $array_key) {
							$controller = $value['controller'];
							$method = !empty($routes[1]) ? $routes[1] : $value['method'];
							$this->prepareParams($routes);
							$this->prepareRoute($controller, $method);
						}
					}
				} else {

					throw new routeException('controller "' . $array_key . '" �� ����� � ������� routes', 404);
				}
			}
			catch (Exception $e) {
				throw $e;
			}
		}

	/**
	 * err 404
	 *
	 * @internal param $err
	 */
	protected function get404()
		{
			try {
				if (!DEBUG_MODE) {
					$controller = $this->site_routes['error404']['controller'];
					$method = $this->site_routes['error404']['method'];
					$this->prepareRoute($controller, $method);
				}

			}
			catch (Exception $e) {
				throw $e;
			}
		}


	/**
	 * Preparing controllerto be included. Checking is controller exists.
	 * Creating new specific model instance. Creating controller instance.
	 *
	 * @param $controller string Controller name.
	 * @param $method     string Method name.
	 *
	 * @throws Exception
	 */
	protected function prepareRoute($controller, $method)
		{
			try {
				$controller_path = SITE_PATH . 'system' . DS . $controller . '.php';
				$this->checkControllerExists($controller_path);
				$this->createModelInstance($controller);
				$this->createInstance($controller, $method);
			}
			catch (Exception $e) {
				throw $e;
			}
		}

	/**
	 * Checks requested URL on params and id and if exists sets to the private vars.
	 *
	 * @param $routes array Requested URL.
	 */
	protected function prepareParams($routes)
		{
			if (!empty($routes[2])) {
				$this->id = $routes[2];
			}
			if ((!empty($routes[3]))) {
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
			if (file_exists($controller_path)) {
				/** @noinspection PhpIncludeInspection */
				require_once $controller_path;
			} else {
				throw new routeException('���� �����������: "' . $controller_path . '" �� ������', 404);
			}
		}

	/**
	 * Creating new instance that required by URL.
	 *
	 * @param $controller string Controller name.
	 * @param $method     string Method name.
	 *
	 * @throws routeException
	 */
	protected function createInstance($controller, $method)
		{
			$instance = new $controller;

			if (method_exists($instance, $method)) {
				$reflection = new ReflectionMethod($instance, $method);
				if ($reflection->isPublic()) {
					$instance->$method($this->id, $this->param);
				} else {
					throw new routeException('����� "' . $method . '" �� �������� ���������');
				}
			} else {
				throw new routeException('����� "' . $method . '" �� ������ � ����������� "' . $controller . '"', 404);
			}
		}

	/**
	 * Creates instance of model by requested controller.
	 *
	 * @param $controller string Controller name.
	 *
	 * @throws routeException
	 */
	protected function createModelInstance($controller)
		{

			$model = SITE_PATH . 'system' . DS . 'models' . DS . $controller . DS . $controller . '.php';

			if (file_exists($model)) {
				/** @noinspection PhpIncludeInspection */
				require_once($model);
			} else {
				throw new routeException('���� ������: "' . $model . '" �� ������', 404);
			}
		}
}

/**
 * Class routeException
 */
class routeException extends Exception
{

	/**
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct($message = '', $code = 0)
		{
			parent::__construct($message, $code);

			if (DEBUG_MODE) {
				die('<b>������ ' . $code . ':</b> ' . $message . '<br>');
			}
			log($message);
			header('Location: /404.php', '', 404);
		}

}