<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 15:46
 */


namespace lib\pattern;

/**
 * Singleton
 *
 * @package  Common
 */
class Singletone
{
	/**
	 * @var static Singleton instance
	 */
	protected static $instance;

	/**
	 * Get instance
	 * @return static::$instance
	 */
	final public static function getInstance()
		{
			return isset(static::$instance)
				? static::$instance
				: static::$instance = new static;
		}

	/**
	 * Disabled by access level
	 */
	protected function __construct()
		{

		}

	/**
	 * Disabled by access level
	 */
	protected function __clone()
		{

		}
}