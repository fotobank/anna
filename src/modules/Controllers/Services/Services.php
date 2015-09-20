<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Services.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     16.07.2015
 * @time:     1:21
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Services;

use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;

/**
 * Class Services
 * @package modules\Controllers\Services
 */
class Services extends Controller
{

    /**
     * @param \modules\Models\Model\Model $model
     * @param \modules\Views\View\View    $view
     */
    public function __construct(Model $model, View $view)
    {
        $this->model = $model;
        $this->view  = $view;
    }

    /**
     * экшен
     *
     * @return string
     * @throws \Exception
     */
    public function services() {

        try
        {
            $this->model->attach($this->view);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}