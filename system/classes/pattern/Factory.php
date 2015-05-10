<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 16:28
 */

/**
 * Пул объектов
 */
class Factory
{

	/**
	 * @var $products[]
	 */
	protected static $products = [ ];


	/**
	 * Добавляет продукт в пул
	 *
	 * @param $product
	 * @return void
	 */
	public static function push($product)
	{
		self::$products[$product->get()] = $product;
	}

	/**
	 * Возвращает продукт из пула
	 *
	 * @param integer|string $id - идентификатор продукта
	 * @return $product
	 */
	public static function get($id)
	{
		return isset(self::$products[$id]) ? self::$products[$id] : null;
	}

	/**
	 * Удаляет продукт из пула
	 *
	 * @param integer|string $id - идентификатор продукта
	 * @return void
	 */
	public static function remove($id)
	{
		if (array_key_exists($id, self::$products)) {
			unset(self::$products[$id]);
		}
	}
}