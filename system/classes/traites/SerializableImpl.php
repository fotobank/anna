<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 09.05.2015
 * Time: 1:48
 */

/**
 * Example class to test aspects
 */
trait SerializableImpl
{
	/**
	 * String representation of object
	 * @return string the string representation of the object or null
	 */
	public function serialize()
	{
		return serialize(get_object_vars($this));
	}

	/**
	 * Constructs the object
	 * @param string $serialized The string representation of the object.
	 *
	 * @return mixed the original value unserialized.
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);
		foreach($data as $key=>$value) {
			$this->$key = $value;
		}
	}
}
