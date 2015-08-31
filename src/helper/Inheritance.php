<?php
/**
 * ����� ������������ ��� �������������� ������������
 *
 * @created   by PhpStorm
 * @package   Inheritance.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     31.08.2015
 * @time:     10:14
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper;



/**
 * Class Inheritance
 *
 * ������:
 * 1-� ������� �����:
 * class Mother extends Inheritance {...}
 *
 * 2-� ������� �����:
 * class Father extends Inheritance {...}
 *
 * class Son extends Inheritance {
 *     public function __construct() {
 *         �������, ��� ����� Son ��������� ������ Mother � Father
 *         $this->addInheritance(new Mother);
 *         $this->addInheritance(new Father);
 *   }
 * }
 *
 * ������������� ������������
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
        // ��� ����� ������������� ������ �������
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
