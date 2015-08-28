<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 16:28
 */

namespace lib\pattern;
/**
 * ��� ��������
 */
class Factory
{

	private $registryClass = [ ];

	/**
	 * �������
	 * ��������� ����� ������ � ���
	 * @param $className
	 * @param $data
	 *
	 * @return object
	 */
	public function push( $className, $data = false ) {

		if ( class_exists( $className ) ) {

			if ( empty( $this->registryClass[$className] ) ) {
				$this->registryClass[$className] = new $className($data);
			}

			return $this->registryClass[$className];
		}
		return false;
	}

	/**
	 * ���������� ������� �� ����
	 *
	 * @param integer|string $className - ������������� ��������
	 * @return $className
	 */
	public function get($className)
	{
		return isset($this->registryClass[$className]) ? $this->registryClass[$className] : null;
	}

	/**
	 * ������� ������� �� ����
	 *
	 * @param integer|string $className - ������������� ��������
	 * @return void
	 */
	public function remove($className)
	{
		if (array_key_exists($className, $this->registryClass)) {
			unset($this->registryClass[$className]);
		}
	}
}