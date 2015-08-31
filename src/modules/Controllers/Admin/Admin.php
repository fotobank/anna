<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Admin.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     26.07.2015
 * @time:     0:46
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Admin;


use modules\Controllers\Controller\Controller;
use modules\Models\Admin as model;


/**
 * Class Admin
 * @package modules\Controllers\Admin
 */
class Admin extends Controller
{
    /**
     * ������������� ������� Mustache
     */
    public function __construct()
    {
        parent::init();
    }

    /**
     * �����
     */
    public function admin() {
        $model = new model\Admin();
        return $this->mustache->render('admin/admin', $model);
    }
}