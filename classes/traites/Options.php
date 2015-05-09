<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 08.05.2015
 * Time: 23:38
 */

trait Options {
	/**
	 * @param array $options
	 */
	public function setOptions(array $options)
	{
		// apply options
		foreach ($options as $key => $value) {
			$method = 'set' . $this->normalizeKey($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	private function normalizeKey($key)
	{
		$option = str_replace('_', ' ', strtolower($key));
		$option = str_replace(' ', '', ucwords($option));
		return $option;
	}
}