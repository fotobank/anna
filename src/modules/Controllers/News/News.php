<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   News.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     12:59
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\News;

use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;


/**
 * Class News
 * @package modules\Controllers\News
 */
class News  extends Controller
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
     * @throws \phpbrowscap\Exception
     */
    public function news() {
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