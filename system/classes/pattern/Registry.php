<?php

/**
 * Created by PhpStorm.
 * User: Jurii
 *
 * Получаем объект реестра
 * $Registry = Registry::getInstance();
 *
 * Записываем значения в реестр
 * $Registry['one'] = "1";
 * $Registry['two'] = "2";
 * $Registry['three'] = "3";
 * А можно так
 * $Registry->four = "4";
 *
 * Выводим значения
 * echo $Registry['two'];
 * Выведет 2
 *
 * echo $Registry->three;
 * Выведет 3
 *
 * echo count($Registry);
 * Выведет 4
 *
 * foreach ($Registry as $Key => $Value)
 * {
 * echo $Key."|".$Value."\r\n";
 * }
 * Выведет:
 * one|1
 * two|2
 * three|3
 * four|4
 *
 */
class Registry implements ArrayAccess, Iterator, Countable {

	//Здесь хранятся переменные
	private $_Vars;
	//Внутренний счетчик
	private $_Counter = 0;

	use Singleton;


	/**
	 * Устанавливает значение переменной
	 *
	 * @param $Name
	 * @param $Value
	 */
	function set($Name, $Value) {
		if (empty($Name)) {
			$this->_Vars[] = $Value;
		} else {
			$this->_Vars[$Name] = $Value;
		}
	}

	/**
	 * Возвращает значение переменной
	 *
	 * @param $Name
	 *
	 * @return null
	 */
	function get($Name) {
		if (isset($this->_Vars[$Name])) {
			return $this->_Vars[$Name];
		}

		return null;
	}

	/**
	 * @param $Name
	 * @param $Value
	 */
	function __set($Name, $Value) {
		$this->set($Name, $Value);
	}

	/**
	 * @param $Name
	 *
	 * @return null
	 */
	function __get($Name) {
		return $this->get($Name);
	}


	/**
	 * Возвращает кол-во хранимых переменных
	 *
	 * @return int
	 */
	function count() {
		return count($this->_Vars);
	}

	/**
	 * @param mixed $Name
	 *
	 * @return bool
	 */
	function offsetExists($Name) {
		return isset($this->_Vars[$Name]);
	}

	/**
	 * @param mixed $Name
	 * @param mixed $Value
	 */
	function offsetSet($Name, $Value) {
		$this->set($Name, $Value);
	}

	/**
	 * @param mixed $Name
	 *
	 * @return null
	 */
	function offsetGet($Name) {
		return $this->get($Name);
	}

	/**
	 * @param mixed $Name
	 */
	function offsetUnset($Name) {
		if (isset($this->_Vars[$Name])) {
			unset($this->_Vars[$Name]);
		}
	}

	/**
	 * @return null
	 */
	function current() {
		$Key = $this->key();

		return $this->get($Key);
	}

	/**
	 *
	 */
	function next() {
		$this->_Counter ++;
	}

	/**
	 *
	 */
	function rewind() {
		$this->_Counter = 0;
	}

	/**
	 * @return mixed
	 */
	function key() {
		reset($this->_Vars);
		for ($i = 0; $i < $this->_Counter; $i ++) {
			next($this->_Vars);
		}

		return key($this->_Vars);
	}

	/**
	 * @return bool
	 */
	function valid() {
		$Key = $this->key();

		return isset($this->_Vars[$Key]);
	}

}