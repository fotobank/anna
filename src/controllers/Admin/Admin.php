<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Admin.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     26.07.2015
 * @time:     0:46
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Admin;


use controllers\Controller\Controller;
use models\Admin as model;


/**
 * Class Admin
 * @package controllers\Admin
 */
class Admin extends Controller
{
    /**
     * инициализация вьювера Mustache
     */
    public function __construct()
    {
        parent::init();
    }

    /**
     * экшен
     */
    public function admin() {
        $model = new model\Admin();
        echo $this->mustache->render('admin/admin', $model);
    }
}