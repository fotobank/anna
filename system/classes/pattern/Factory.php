<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 16:28
 */

/**
 * ��� ��������
 */
class Factory
{

	/**
	 * @var $products[]
	 */
	protected static $products = [ ];


	/**
	 * ��������� ������� � ���
	 *
	 * @param $product
	 * @return void
	 */
	public static function push($product)
	{
		self::$products[$product->get()] = $product;
	}

	/**
	 * ���������� ������� �� ����
	 *
	 * @param integer|string $id - ������������� ��������
	 * @return $product
	 */
	public static function get($id)
	{
		return isset(self::$products[$id]) ? self::$products[$id] : null;
	}

	/**
	 * ������� ������� �� ����
	 *
	 * @param integer|string $id - ������������� ��������
	 * @return void
	 */
	public static function remove($id)
	{
		if (array_key_exists($id, self::$products)) {
			unset(self::$products[$id]);
		}
	}
}