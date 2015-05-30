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
	 * ������ ���� �� ����� � �������������
	 */
	function setPath($path)
		{
			$path .= DS;
			// ���� ���� �� ����������, ������������� �� ����
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
	 * ����������� ����������� � ������ �� ����
	 */
	private function getController(&$file, &$controller, &$action, &$args)
		{
			$route = (empty($_GET['route'])) ? '' : $_GET['route'];
			unset($_GET['route']);
			if (empty($route)) {
				$route = 'Index';
			}

			// �������� ����� ����
			$route = trim($route, '/\\');
			$parts = explode('/', $route);

			// ������� ����������
			$cmd_path = $this->path;
			foreach ($parts as $part) {

				$fullpath = $cmd_path . ucfirst($part);

				// �������� ������������� �����
				if (is_dir($fullpath)) {
					$cmd_path .= ucfirst($part) . DS;
					array_shift($parts);
					continue;
				}

				// ������� ����
				if (is_file($fullpath)) {
					$controller = $part;
					array_shift($parts);
					break;
				}
			}

			// ���� ���� �� ������ ����������, �� ���������� ����������� index
			if (empty($controller)) {
				$controller = 'Index';
			}

			// �������� �����
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
			try {
				// ����������� ����
				$this->getController($file, $controller, $action, $args);

				// �������� ������������� �����, ����� 404
				if (is_readable($file) === false) {
					header('Location: error404');
				}

				// ���������� ����
				/** @noinspection PhpIncludeInspection */
				require_once($file);

				// ������ ��������� �����������
				$class = 'controllers' . '\\' . $controller . '\\' . $controller;
				$controller = new $class();

				// ���� ����� �� ���������� - 404
				if (is_callable([$controller, $action]) === false) {
					throw new Exception ('action: `' . $action . '` not found');
				}

				// ��������� �����
				$controller->$action();
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					echo ("<b>������:</b> " . $e->getMessage() . "<br>���������� �������� � ����� '" . $e->getFile() .
						 "' �� ����� <b>'" . $e->getLine() . "'</b><br>");
				}
			}
		}
}