<?php
/**
 *  ласс предназначен дл€ множественного наследовани€
 *
 * @created   by PhpStorm
 * @package   Inheritance.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     31.08.2015
 * @time:     10:14
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper;



/**
 * Class Inheritance
 *
 * ѕример:
 * 1-й базовый класс:
 * class Mother extends Inheritance {...}
 *
 * 2-й базовый класс:
 * class Father extends Inheritance {...}
 *
 * class Son extends Inheritance {
 *     public function __construct() {
 *         говорим, что класс Son наследует классы Mother и Father
 *         $this->addInheritance(new Mother);
 *         $this->addInheritance(new Father);
 *   }
 * }
 *
 * ћножественное наследование
 *
 * @package helper
 */
abstract class Inheritance {

    private $inheritances = [];

    /**
     * @param       $method
     * @param array $arguments
     */
    public function __call($method, array $arguments = []) {
        // тут будем перехватывать вызовы методов
        /** @var Inheritance $inheritance */
        foreach ($this->inheritances as $inheritance) {
            $inheritance->invoke($method, $arguments);
        }
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function invoke($method, $arguments) {
        if (is_callable([$this, $method])) {
            return call_user_func_array([$this, $method], $arguments);
        }
        return false;
    }

    /**
     * @param \helper\Inheritance $inheritance
     */
    protected function addInheritance(Inheritance $inheritance) {
        $this->inheritances[get_class($inheritance)] = $inheritance;
    }
}
