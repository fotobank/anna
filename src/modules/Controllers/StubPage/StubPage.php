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
use modules\Models\StubPage\StubPage as ModelStubPage;
use view\View;

/**
 * Class StubPage
 * @package modules\Controllers\EmailStubPage
 */
class StubPage extends Controller
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
     * @throws \phpbrowscap\Exception
     */
    public function stubPage() {
        try
        {
            $model = new ModelStubPage([]);

            return $this->viewer->render('stubPage', $model);
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