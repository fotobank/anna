<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   StubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     16.07.2015
 * @time:     17:01
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\StubPage;

use modules\Controllers\Controller\Controller;
use modules\Models\Model\Model;
use modules\Views\View\View;

/**
 * Class StubPage
 * @package modules\Controllers\EmailStubPage
 */
class StubPage extends Controller
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
    public function stubPage() {
        try
        {
            $this->model->attach($this->view);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * экшен
     *
     * @throws \phpbrowscap\Exception
     */
    public function toEmail() {
        try
        {
            $model = new ModelStubPage();
            $mess  = $model->toEmail();

            header('Content-Type: application/json');
            echo $mess;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}