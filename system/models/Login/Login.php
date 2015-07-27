<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Login.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     26.07.2015
 * @time:     0:46
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\Login;

use models\Base as model;


/**
 * Class Login
 * @package models\Login
 */
class Login extends model\Base
{

    /**
     * @param $options
     */
    public function __construct($options = [])
    {
        // ��������� ������� ������
        $this->setOptions($options);
        // ������������� ������������ ������������� ������
        parent::__construct();

    }

}