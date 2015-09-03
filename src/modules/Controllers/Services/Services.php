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
use modules\Models\Services\Services as ModelServices;
use view\View;

/**
 * Class Services
 * @package modules\Controllers\Services
 */
class Services extends Controller
{

    /**
     * инициализация вьювера
     *
     * @param \view\View $view
     *
     */
    public function __construct(View $view)
    {
        $this->viewer = $view;
    }

    /**
     * экшен
     *
     * @param $dates
     *
     * @return string
     * @throws \Exception
     */
    public function services($dates) {

        try
        {
            $model = new ModelServices($dates);

            return $this->viewer->render('services', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}