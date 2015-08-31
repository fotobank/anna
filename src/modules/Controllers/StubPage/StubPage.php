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
use modules\Models\StubPage as model;

/**
 * Class StubPage
 * @package modules\Controllers\EmailStubPage
 */
class StubPage extends Controller
{

    /**
     * инициализация вьювера Mustache
     */
    public function __construct()
    {
        parent::init();
    }

    /**
     * экшен
     *
     * @throws \phpbrowscap\Exception
     */
    public function stubPage() {
        $model = new model\StubPage([

        ]);
        return $this->mustache->render('stubPage', $model);
    }

    /**
     * экшен
     *
     * @throws \phpbrowscap\Exception
     */
    public function toEmail() {
        $model = new model\StubPage();
        $mess = $model->toEmail();
        header('Content-Type: application/json');
        return $mess;
    }
}