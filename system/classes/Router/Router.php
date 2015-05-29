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
Class Router
{

	private $path;

	/**
	 *
	 */
	function __construct()
		{

		}


	/**
	 * @param $path
	 *
	 * @throws Exception
	 *
	 * задаем путь до папки с контроллерами
	 */
	function setPath($path)
		{
			$path .= DS;
			// если путь не существует, сигнализируем об этом
			if (is_dir($path) === false) {
				throw new Exception ('Invalid controller path: `' . $path . '`');
			}
			$this->path = $path;
		}

	/**
	 * @param $file
	 * @param $controller
	 * @param $action
	 * @param $args
	 * определение контроллера и экшена из урла
	 */
	private function getController(&$file, &$controller, &$action, &$args)
		{
			$route = (empty($_GET['route'])) ? '' : $_GET['route'];
			unset($_GET['route']);
			if (empty($route)) {
				$route = 'Index';
			}

			// Получаем части урла
			$route = trim($route, '/\\');
			$parts = explode('/', $route);

			// Находим контроллер
			$cmd_path = $this->path;
			foreach ($parts as $part) {
				$fullpath = $cmd_path . $part;

				// Проверка существования папки
				if (is_dir($fullpath)) {
					$cmd_path .= $part . DS;
					array_shift($parts);
					continue;
				}

				// Находим файл
				if (is_file($fullpath . '.php')) {
					$controller = $part;
					array_shift($parts);
					break;
				}
			}

			// если урле не указан контролер, то испольлзуем поумолчанию index
			if (empty($controller)) {
				$controller = 'index';
			}

			// Получаем экшен
			$action = array_shift($parts);
			if (empty($action)) {
				$action = 'index';
			}

			$file = $cmd_path . $controller . '.php';
			$args = $parts;
		}

	/**
	 *
	 */
	function start()
		{
			// Анализируем путь
			$this->getController($file, $controller, $action, $args);

			// Проверка существования файла, иначе 404
			if (is_readable($file) === false) {
				die ('file class not found');
			}

			// Подключаем файл
			/** @noinspection PhpIncludeInspection */
			include($file);

			// Создаём экземпляр контроллера
			$className = ucfirst($controller);
			$class = 'controllers' . '\\' .  $className . '\\' .  $className;
			$controller = new $class();

			// Если экшен не существует - 404
			if (is_callable([$controller, $action]) === false) {
				die ('action not found');
			}

			// Выполняем экшен
			$controller->$action();
		}
}