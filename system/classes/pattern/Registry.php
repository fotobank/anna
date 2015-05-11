<?php

/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 10:45
 */
class Registry {

	private static $_instance = null;

	private $_registry = [ ];


	/**
	 * @return null|Registry
	 */
	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * @param $key
	 * @param $object
	 */
	public static function set( $key, $object ) {

		self::getInstance()->_registry[$key] = $object;

	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public static function get( $key ) {

		self::getInstance();

		if ( !empty( self::getInstance()->_registry[$key] ) ) {
			return self::getInstance()->_registry[$key];
		}
		return false;
	}

	/**
	 * Фабрика
	 *
	 * @param $class_name
	 * @param $data
	 *
	 * @return object
	 */
	public static function factory( $class_name, $data = false ) {
		if ( class_exists( $class_name ) ) {

			if ( empty( self::getInstance()->_registry[$class_name] ) ) {
				self::getInstance()->_registry[$class_name] = new $class_name($data);
			}

			return self::getInstance()->_registry[$class_name];
		}
		return false;
	}

	public static function clear() {
		self::$_instance = null;
	}

	/**
	 * __construct
	 *
	 */
	private function __construct() {
	}

	/**
	 * Защищаем от создания через клонирование
	 *
	 * @return Singleton
	 */
	private function __clone() {
	}

	/**
	 * Защищаем от создания через unserialize
	 *
	 * @return Singleton
	 */
	private function __wakeup() {
	}
}