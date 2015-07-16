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

namespace controllers\Services;

use controllers\Controller\Controller;
use models\Services as model;


/**
 * Class Services
 * @package controllers\Services
 */
class Services extends Controller
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
    public function services($dates) {

        $model = new model\Services($dates);
        echo $this->mustache->render('services', $model);

    }


}