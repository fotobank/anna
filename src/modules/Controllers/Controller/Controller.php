<?php
/**
 * ����� Controller
 *
 * @created   by PhpStorm
 * @package   Controller.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     29.05.2015
 * @time      :     14:55
 * @license   MIT License: http://opensource.org/licenses/MIT
 * @formatter:off
 */

namespace modules\Controllers\Controller;


use common\Helper;


/**
 * Class Controller
 *
 * @package controllers
 * @formatter:off
 *
 */
abstract class Controller
{
    use Helper;

    /** @var  \modules\Models\Model\Model $model */
    protected $model;

    /** @var  \modules\Views\View\View */
	protected $view;

    public function __destruct()
    {
        $this->model->notify();
    }

}