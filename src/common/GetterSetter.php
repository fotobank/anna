<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 1:04
 */

namespace common;

/**
 * Class GetterSetter
 * @package common\Container
 */
trait GetterSetter
{
	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name)
	{
		$getter = 'get' . ucfirst($name);
		if (!method_exists($this, $getter))
		{
			throw new \Exception('Not found getter for property - ' . $name);
		}
		return $this->$getter();
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function __set($name, $value)
	{
		$setter = 'set' . ucfirst($name);
		if (!method_exists($this, $setter))
		{
			throw new \Exception('Not found setter for property - ' . $name);
		}
		$this->$setter($value);
		return $this;
	}
}