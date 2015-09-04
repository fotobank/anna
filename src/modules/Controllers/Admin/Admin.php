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
use modules\Models\Admin\Admin as ModelAdmin;
use view\View;

/**
 * Class Admin
 * @package modules\Controllers\Admin
 */
class Admin extends Controller
{
    /**
     * ������������� �������
     *
     * @param \view\View $view
     *
     */
    public function __construct(View $view)
    {
        $this->viewer = $view;
    }

    /**
     * �����
     */
    public function admin() {
        try
        {
            $model = new ModelAdmin();

            return $this->viewer->render('admin/admin', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}