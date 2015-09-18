<?php
/**
 * Класс Controller About
 *
 * @created   by PhpStorm
 * @package   About.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     18.06.2015
 * @time:     19:35
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\About;

use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;


/**
 * Class About
 *
 * @package modules\Controllers\About
 */
class About extends Controller
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
    public function about()
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