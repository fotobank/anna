<?php

/**
 * Class Magic
 * PHP magic wrapper
 *
 * пример:
 * protected $data;
 * public function exists($key) { return array_key_exists($key, $this->data); }
 * public function set($key, $val) { $this->data[$key] = $val; }
 * public function &get($key) { return $this->data[$key]; }
 * public function clear($key) { unset($this->data[$key]); }
 *
 * class Foo() extend Magic { ... }
 * $t =new Foo()
 * $t->test = 1; // set('test', 1)
 * exho $t->test; // 1
 *
 */
abstract class Magic implements ArrayAccess
{
    /**
     *    Вернуться true, если ключ не пуст
     * @return bool
     * @param $key string
     **/
    public abstract function exists($key);

    /**
     * Привязать значение ключа
     * @return mixed
     * @param $key string
     * @param $val mixed
     **/
    public abstract function set($key, $val);

    /**
     * Получить содержимое ключа
     * @return mixed
     * @param $key string
     **/
    public abstract function &get($key);

    /**
     * Удалить ключ
     * @return NULL
     * @param $key string
     **/
    public abstract function clear($key);

    /**
     * метод для проверки значения key
     * @return mixed
     * @param $key string
     **/
    public function __isset($key)
    {
        return $this->visible($this, $key) ? isset($this->$key) : $this->exists($key);
    }

    /**
     * метод для проверки значения value
     * @return mixed
     * @param $key string
     * @param $val
     **/
    public function __set($key, $val)
    {
        return $this->visible($this, $key) ? ($this->$key = $val) : $this->set($key, $val);
    }

    /**
     * метод для получения значения свойства
     *    Alias for offsetget()
     * @return mixed
     * @param $key string
     **/
    public function &__get($key)
    {
        if ($this->visible($this, $key)) {
            $val = &$this->$key;
        } else {
            $val = &$this->get($key);
        }
        return $val;
    }

    /**
     *  метод для удаления значения свойства
     *    Alias for offsetunset()
     * @return NULL
     * @param $key string
     **/
    public function __unset($key)
    {
        if ($this->visible($this, $key)) {
            unset($this->$key);
        } else {
            $this->clear($key);
        }
    }

    /**
     *    Return TRUE if property has public visibility
     * @return bool
     * @param $obj object
     * @param $key string
     **/
    public function visible($obj, $key)
    {
        if (property_exists($obj, $key)) {
            $ref = new ReflectionProperty(get_class($obj), $key);
            $out = $ref->isPublic();
            unset($ref);
            return $out;
        }
        return FALSE;
    }
}
