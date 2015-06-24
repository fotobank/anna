<?php
/**
 * 1. Свойства класса должны быть заданны
 * 2. Имена свойств должны быть вида: properti_name - с маленькой буквы
 * слова разделяются полочкой
 * 3. При задании свойств через неопределенный в классе метод
 * имя метода долно быть в верблюжьем стиле: (get|set)PropertyName($prop)
 */


namespace Common\Container;

use Exception;

/**
 * Options Trait
 *
 * Example of usage
 * 1)
 *    $obj = new Class();
 *    $obj->(get|set)PropertyName($prop); - запись в верблюжьем стиле
 * 2)
 *     class Foo
 *     {
 *       use Options;
 *
 *       protected $bar = '';
 *       protected $baz = '';
 *
 *       public function setBar($value)
 *       {
 *           $this->bar = $value;
 *       }
 *
 *       public function setBaz($value)
 *       {
 *           $this->baz = $value;
 *       }
 *     }
 *
 *     $Foo = new Foo(array('bar'=>123, 'baz'=>456));
 *
 * @created  12.07.11 16:15
 */
trait Options
{
	/**
	 * @var array Options store
	 */
	protected $options;

	/**
	 * Получение и установка свойств объекта через вызов магического метода вида:
	 * $object->(get|set)PropertyName($prop);
	 *
	 * @see __call
	 *
	 * @param $method_name
	 * @param $argument
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method_name, $argument)
		{
			try {
				$args = preg_split('/(?<=\w)(?=[A-Z])/', $method_name);
				$action = array_shift($args);
				$property_name = strtolower(implode('_', $args));
				switch ($action) {
					case 'get':
						return isset($this->$property_name) ? $this->$property_name : null;

					case 'set':
						if (property_exists($this, $property_name)) {
							$this->$property_name = $argument[0];

							return $this;
						} else {
				throw new Exception('не найдено свойство класса "' .$property_name . '" в классе "' .__CLASS__. '"<br>');
						}
				}
				throw new Exception("неправильно заданно имя аргумента. Необходимо: (get|set)PropertyName, имеем: '{$method_name}'<br>");
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					throw new Exception($e->getMessage(), E_USER_ERROR);
				}
			}
			return $this;
		}


	/**
	 * Get option by key
	 *
	 * @param string      $key
	 * @param string|null $subKey
	 *
	 * @return mixed
	 */
	public function getOption($key, $subKey = null)
		{
			if (array_key_exists($key, $this->options)) {
				if (0 !== $subKey) {
					return isset($this->options[$key][$subKey]) ? $this->options[$key][$subKey] : null;
				} else {
					return $this->options[$key];
				}
			} else {
				return null;
			}
		}

	/**
	 * Set option by key over setter
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	public function setOption($key, $value)
		{

			$method = 'set'.$this->normalizeKey($key);
			$this->$method($value);
			/*if (method_exists($this, $method)) {
				$this->$method($value);
			} else {
				$this->options[$key] = $value;
			}*/
		}

	/**
	 * Get all options
	 * @return array
	 */
	public function getOptions()
		{
			return $this->options;
		}

	/**
	 * Setup, check and init options
	 *
	 * Requirements
	 * - options must be a array
	 * - options can be null
	 *
	 * @param array $options
	 *
	 * @return self
	 */
	public function setOptions($options)
		{
			// store options by default
			$this->options = (array) $options;

			// apply options
			foreach ($this->options as $key => $value) {
				$this->setOption($key, $value);
			}

			return $this;
		}

	/**
	 * Normalize key name
	 *
	 * @param  string $key
	 *
	 * @return string
	 */
	private function normalizeKey($key)
		{
			$option = str_replace(['_', '-'], ' ', strtolower($key));
			$option = str_replace(' ', '', ucwords($option));

			return $option;
		}
}

