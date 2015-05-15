<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 08.05.2015
 * Time: 23:39
 */

namespace Common\Container;

/**
 * Class Singleton_
 * @package Common
 */
trait Singleton_
{
	// protected static $instance;

	/**
	 *
	 */
	/*protected function __construct()
	{
		static::setInstance($this);
	}*/

	/**
	 * @param $instance
	 *
	 * @return mixed
	 */
	/*final public static function setInstance($instance)
	{
		static::$instance = $instance;
		return static::$instance;
	}*/

	/**
	 * @return static
	 */
	/*final public static function getInstance()
	{
		if(isset(static::$instance)) {
			return static::$instance;
		} else {
			static::$instance = new static;
			return static::$instance;
		}
	}*/

// -------------- 2 ----------------------

	static public function getInstance()
	{
		static $instance = null;
		if ($instance === null) {
			$instance = new static();
		}
		return $instance;
	}

	/**
	 *  __construct()
	 */
	private function __construct() { }

	/**
	 *  __destruct()
	 */
	function __destruct() {
	}

	/**
	 *  __clone()
	 */
	protected function __clone() {
	}

	/**
	 *  __wakeup()
	 */
	protected function __wakeup() {
	}
}