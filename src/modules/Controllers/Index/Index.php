<?php
/**
 * Класс Index
 *
 * @created   by PhpStorm
 * @package   Index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.05.2015
 * @time:     15:05
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Index;

use Exception;
use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;


/**
 * Class controller_Index
 *
 * @package modules\Controllers\Index
 */
class Index extends Controller
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
     * @throws Exception
     */
    public function index()
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