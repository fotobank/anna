<?php
/**
 * Класс предназначен для редактирования ini файлов со структурой подобной php.ini
 * @created   by PhpStorm
 * @package   Ini.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     16.05.2015
 * @time:     0:04
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace Ini;

use ArrayAccess;
use Countable;

/**
 * Class Ini
 * @package Ini
 */
class Ini extends Ini_Exception implements ArrayAccess, Countable
{
	private static $instance;

	private $autosave = false;

	private $ext = '.ini';

	private $delimiter;

	private $file;

	private $data;

	/**
	 * @param        $file
	 * @param string $delimiter
	 * @param bool   $autocreate
	 */
	public function __construct($file, $delimiter='.', $autocreate=false)
		{
			if ($autocreate && !file_exists($file.$this->ext))
				file_put_contents($file.$this->ext, '');
			$this->file = realpath($file.$this->ext);
			$this->data = parse_ini_file($this->file, 1);
			$this->delimiter = $delimiter;
		}

	/**
	 * @param null   $file
	 * @param string $delimiter
	 * @param bool   $autocreate
	 *
	 * @return Ini
	 */
	public static function factory($file=null, $delimiter='.', $autocreate=false)
		{
			if (!self::$instance && $file)
				self::$instance = new self($file, $delimiter, $autocreate);
			return self::$instance;
		}

	/**
	 * @param bool $status
	 *
	 * @return $this
	 * @throws Ini_Exception
	 */
	public function setAutosave($status=false)
		{
			if (!is_bool($status))
				throw new Ini_Exception('Invalid parameter data type');
			$this->autosave = $status;
			return $this;
		}

	/**
	 * @return bool
	 */
	public function getAutosaveStatus()
		{
			return $this->autosave;
		}

	/**
	 * @param null $path
	 *
	 * @return array|null
	 */
	public function get($path=null)
		{
			if (!$path) return $this->data;
			$path = explode($this->delimiter, $path);
			if (count($path) == 1){
				return isset($this->data[$path[0]]) ? $this->data[$path[0]] : null;
			}
			elseif (count($path) == 2){
				return isset($this->data[$path[0]][$path[1]]) ? $this->data[$path[0]][$path[1]] : null;
			}
			return null;
		}

	/**
	 * @param $path
	 * @param $value
	 *
	 * @return $this
	 * @throws Ini_Exception
	 */
	public function set($path, $value)
		{
			$path = explode($this->delimiter, $path);
			if (count($path) == 1){
				if (!is_array($value))
					throw new Ini_Exception('Incorrect data type values');
				$this->data[$path[0]] = $value;
			}
			elseif (count($path) == 2){
				if (is_array($value) || is_object($value))
					throw new Ini_Exception('Incorrect data type values');
				$this->data[$path[0]][$path[1]] = $value;
			}

			if ($this->autosave)
				$this->save();

			return $this;
		}

	/**
	 * @param $path
	 *
	 * @return $this|array
	 */
	public function del($path)
		{
			if (!$path) return $this->data;
			$path = explode($this->delimiter, $path);
			if (count($path) == 1){
				unset($this->data[$path[0]]);
			}
			elseif (count($path) == 2){
				unset($this->data[$path[0]][$path[1]]);
			}

			if ($this->autosave)
				$this->save();

			return $this;
		}

	/**
	 * @param $path
	 *
	 * @return array|bool
	 */
	public function is($path)
		{
			if (!$path) return $this->data;
			$path = explode($this->delimiter, $path);
			if (count($path) == 1){
				return isset($this->data[$path[0]]);
			}
			elseif (count($path) == 2){
				return isset($this->data[$path[0]][$path[1]]);
			}
			return false;
		}

	/**
	 * @param $path
	 *
	 * @return array|int
	 * @throws Ini_Exception
	 */
	public function size($path)
		{
			if (!$path) return $this->data;
			$path = explode($this->delimiter, $path);
			if (count($path) !== 1)
				throw new Ini_Exception('Incorrect path');

			return count($this->data[$path[0]]);
		}

	/**
	 * @param mixed $offset
	 *
	 * @return array|bool
	 */
	public function offsetExists($offset)
		{
			return $this->is($offset);
		}

	/**
	 * @param mixed $offset
	 *
	 * @return array|null
	 */
	public function offsetGet($offset)
		{
			return $this->get($offset);
		}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws Ini_Exception
	 */
	public function offsetSet($offset, $value)
		{
			$this->set($offset, $value);
		}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset)
		{
			$this->del($offset);
		}

	/**
	 * @return int
	 */
	public function count()
		{
			return count($this->data);
		}

	/**
	 *
	 */
	public function save()
		{
			$str = '';
			foreach ($this->data as $k=>$v){
				$str .= "[$k]\n";
				if (is_array($v)){
					foreach($v as $k_=>$v_){
						$str .= "$k_=$v_\n";
					}
				}
			}
			file_put_contents($this->file, $str);
		}
}