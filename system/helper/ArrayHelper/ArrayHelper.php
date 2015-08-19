<?php

namespace helper\ArrayHelper;
/**
 * Array Helper class
 * @author Thomas Sch�fer
 */
class ArrayHelper
{
    /**
     * holder of values
     * @var array $properties
     */
    protected $properties = [];

    private $flat_properties = [];

    /**
     * @var string $_delimiter
     */
    private $_delimiter = '/';


    public function __clone()
    {
    }

    /**
     * ����� � ������� ��������
     * @param array $data
     * @return $this
     */
    public function setProperties(array &$data)
    {
        if(isset($data))
        {
            $this->properties = &$data;
        }
        return $this;
    }

    /**
     * ������������ ������ ArrayHelper
     * @return ArrayHelper
     */
    public static function init()
    {
        return new ArrayHelper();
    }

    /**
     * �������� key
     * @param $path
     * @return bool
     */
    public function _has($path)
    {
        if(substr($path, -1, 1) == $this->getDelimiter())
        {
            $path = substr($path, 0, -1);
        }
        if(false != strstr($path, $this->getDelimiter()))
        {
            $path = explode($this->getDelimiter(), $path);
            $key  = array_shift($path);
            $path = implode($this->getDelimiter(), $path);
            return $this->hasElement($path, $this->_get($key));
        }
        else
        {
            return array_key_exists($path, $this->properties);
        }
    }

    /**
     * Get value by its key.
     * @param $path
     * @return mixed
     */
    public function has($path = null)
    {
        if($this->get($path) === false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * ���������� ����� ����������� ������� ��� ����� ������� ������
     * returns hidden keys
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->properties);
    }

    /**
     *  ���������� �������� �� ����
     *
     * @param $path
     * @return bool
     */
    public function get($path)
    {
        if(0 !== count($this->properties))
        {
            if(empty($path))
            {
                return $this->properties;
            }
            $arg = $this->arrKey($path);
            if(count($arg) === 1)
            {
                if(substr($path, -1, 1) == '/')
                {
                    $path = substr($path, 0, -1);
                }
                if(array_key_exists($path, $this->properties))
                {
                    return $this->properties[$path];
                }
                else
                {
                    return false;
                }
            }
            $arr = array_shift($arg);
            return $this->multi_array_key_exists($arg, $this->properties[$arr]);
        }
        return false;
    }

    /**
     * @param array|string $array_path
     * @param array $search_array
     * @return bool
     * @internal param $key
     */
    protected function multi_array_key_exists($array_path, $search_array)
    {
        $count = count($array_path) - 1;
        foreach ($array_path as $key => $i_key)
        {
            if(is_array($search_array))
            {
                if(array_key_exists($i_key, $search_array) === false)
                {
                    return false;
                }
            }
            elseif($search_array !== $i_key)
            {
                return false;
            }
            if($key === $count)
            {
                return $search_array[$i_key];
            }
            if(is_array($search_array[$i_key]))
            {
                $search_array = array_shift($search_array);
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * ���������� �������� �� ����
     * @param null $path
     * @return array|mixed
     */
    public function _get($path = null)
    {
        if(empty($path))
        {
            return $this->properties;
        }

        if(false != strstr($path, $this->getDelimiter()))
        {
            if(substr($path, -1, 1) == $this->getDelimiter())
            {
                $path = substr($path, 0, -1);
            }
            $path = explode($this->getDelimiter(), $path);
            $key  = array_shift($path);
            $path = implode($this->getDelimiter(), $path);
            return $this->getElement($path, $this->_get($key));
        }
        if(array_key_exists($path, $this->properties))
        {
            return $this->properties[$path];
        }
        return [];
    }

    /**
     * internal element getter
     * @access protected
     * @param array|string $path
     * @param $data
     * @return mixed
     */
    protected function getElement($path, $data)
    {
        if(!is_array($path) and strstr($path, $this->getDelimiter()))
        {
            $path = explode($this->getDelimiter(), $path);
        }
        if(is_array($path))
        {
            $key  = array_shift($path);
            $path = implode($this->getDelimiter(), $path);
            return $this->getElement($path, $data[$key]);
        }
        else
        {
            if(is_array($data) and array_key_exists($path, $data))
            {
                return $data[(string)$path];
            }
            else
            {
                return $data;
            }
        }
    }

    /**
     * internal check for element key
     * @param $path
     * @param $data
     * @return bool
     */
    protected function hasElement($path, $data)
    {
        if(substr($path, -1, 1) == $this->getDelimiter())
        {
            $path = substr($path, 0, -1);
        }
        if(!is_array($path) and strstr($path, $this->getDelimiter()))
        {
            $path = explode($this->getDelimiter(), $path);
        }
        if(is_array($path))
        {
            $key  = array_shift($path);
            $path = implode($this->getDelimiter(), $path);

            if(!array_key_exists($key, $data))
            {
                return false;
            }

            $dat = $this->hasElement($path, $data[$key]);

            if(is_array($dat))
            {
                return $dat;
            }
            elseif(!empty($dat))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return (is_array($data) and array_key_exists($path, $data) ? true : false);
        }
    }

    /**
     * first level setter (fluent design)
     * �������������� ����
     * @param string $name
     * @param mixed $value
     * @return ArrayHelper
     */
    public function add($name, $value)
    {
        if(!array_key_exists($name, $this->flat_properties))
        {
            $this->flat_properties[$name] = $value;
        }
        if(false != strstr($name, $this->getDelimiter()))
        {
            $values = $this->insertElements($name, $value);
            $k      = key($values);
            if(array_key_exists($k, $this->properties))
            {
                $this->properties = self::array_merge_recursive_distinct($this->properties, $values);
            }
            else
            {
                $this->properties[$k] = $values[$k];
            }
        }
        else
        {
            $this->properties[$name] = $value;
        }
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        if(!array_key_exists($name, $this->flat_properties))
        {
            $this->flat_properties[$name] = $value;
        }
        $data = $this->properties;
        $a    = new ArrayHelper($this->getDelimiter());
        $a->add($name, $value);
        $a                = $a->getAll();
        $this->properties = self::array_merge_recursive_distinct($data, $a);
        unset($a);
        return $this;
    }

    /**
     * ������� ����� ����� �� �������� ����������
     * �������� ������ ���� ������� ����������� � ������� ArrayHelper
     * @param $value
     * @return bool|mixed
     */
    public function find($value)
    {
        if(($x = array_search($value, $this->flat_properties, false)))
        {
            return $x;
        }
        return false;
    }

    /**
     * ������ ������ ����� �������, ����� � ���������� ��� ���������� ������.
     *
     * @param array $array
     * @param bool $preserve_keys
     * @return array
     * @param bool $preserve_keys - ��� ��������� �������: ��������������� ��� ��������� )
     */
    public function flatten($array, $preserve_keys = false)
    {
        if(!$preserve_keys)
        {
            // ensure keys are numeric values to avoid overwritting when array_merge gets called
            $array = array_values($array);
        }
        $flattened_array = [];
        foreach ($array as $k => $v)
        {
            if(is_array($v))
            {
                $flattened_array = array_merge($flattened_array, $this->flatten($v, $preserve_keys));
            }
            elseif($preserve_keys)
            {
                $flattened_array[$k] = $v;
            }
            else
            {
                $flattened_array[] = $v;
            }
        }
        return $flattened_array;
    }

    /**
     * ������� ����� ����������� �������
     *
     * @param $arr
     * @param $arr_one
     *
     * @return array
     */
    public function arrMultiToOne($arr, &$arr_one)
    {
        foreach ($arr as $k => $v)
        {
            $arr_one[] = $k;
            if(is_array($v))
            {
                $this->arrMultiToOne($v, $arr_one);
            }
        }
        return $arr_one;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value)
        {
            if(is_array($value) && isset ($merged [$key]) && is_array($merged [$key]))
            {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            }
            else
            {
                $merged [$key] = $value;
            }
        }
        return $merged;
    }

    /**
     * ��������� ������������� ������ � �������
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function isIn($name, $value)
    {
        if(false != strstr($name, $this->getDelimiter()))
        {
            $path = explode($this->getDelimiter(), $name);
            $key  = array_shift($path);
            $path = implode($this->getDelimiter(), $path);
            $data = $this->getElement($path, $this->properties[$key]);
            if(is_array($data))
            {
                return (in_array($value, $data, true));
            }
            else
            {
                return false;
            }
        }
        else
        {
            $data = $this->properties[$name];
            if(is_array($data))
            {
                return (in_array($value, $data, true));
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * �������� ���� �����������
     * @param string $delimiter used to split path levels
     * @return string
     */
    public function setDelimiter($delimiter = '/')
    {
        if('' == strlen($delimiter))
        {
            return $this;
        }
        $this->_delimiter = $delimiter;
        return $this;
    }

    /**
     * ������� ������������ ���� �����������
     * @return string
     */
    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    /**
     * @static
     * @param string|array $path
     * @param mixed $data
     * @return mixed
     */
    private function insertElements($path, $data)
    {
        if(is_array($path) and count($path) == 1)
        {
            $path = array_shift($path);
        }
        if(is_string($path) and substr($path, -1, 1) == $this->getDelimiter())
        {
            $path = substr($path, 0, -1);
        }
        if(!is_array($path))
        {
            $path = explode($this->getDelimiter(), $path);
        }
        // take last and add as key to properties
        if(($key = array_pop($path)))
        {
            return $this->insertElements($path, [$key => $data]);
        }
        return $data;
    }

    /**
     * ���������� � �������� ���������� ������ ������
     * returns properties
     * @return array
     */
    public function getAll()
    {
        $return           = $this->properties;
        $this->properties = null;
        return $return;
    }

    /**
     * ���������� ������ ������
     * @return array
     */
    public function fetchAll()
    {
        return $this->properties;
    }

    /**
     * �������� ������ ������
     * unset properties
     * @return ArrayHelper
     */
    public function clear()
    {
        $this->properties = null;
        return $this;
    }

    /**
     * ��������� ������ �� ������, ����������� �� ������ �������� � ������ ����������� ������
     * @param string $key
     * @return array
     */
    protected function arrKey($key)
    {
        return array_values(array_filter(explode($this->getDelimiter(), $key)));
    }

    /**
     * Delete a value from session by its key.
     * @param $key
     * @return bool
     */
    public function del($key)
    {
        if($this->has($key))
        {
            $i     = $this->arrKey($key);
            $count = count($i);
            if($count == 1)
            {
                unset($this->properties[$i[0]]);
            }
            elseif($count == 2)
            {
                unset($this->properties[$i[0]][$i[1]]);
            }
            elseif($count == 3)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]]);
            }
            elseif($count == 4)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]]);
            }
            elseif($count == 5)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]]);
            }
            elseif($count == 6)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]]);
            }
            elseif($count == 7)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]]);
            }
            elseif($count == 8)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]][$i[7]]);
            }
            elseif($count == 9)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]][$i[7]][$i[8]]);
            }
            elseif($count == 10)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]][$i[7]][$i[8]][$i[9]]);
            }
            elseif($count == 11)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]][$i[7]][$i[8]][$i[9]][$i[10]]);
            }
            elseif($count == 12)
            {
                unset($this->properties[$i[0]][$i[1]][$i[2]][$i[3]][$i[4]][$i[5]][$i[6]][$i[7]][$i[8]][$i[9]][$i[10]][$i[11]]);
            }
            return true;
        }
        return false;
    }

}