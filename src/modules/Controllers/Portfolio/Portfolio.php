<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Portfolio.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     12:59
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Portfolio;

use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;

/**
 * Class Portfolio
 * @package modules\Controllers\Portfolio
 */
class Portfolio  extends Controller
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
     * @throws \Exception
     */
    public function portfolio()
    {
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