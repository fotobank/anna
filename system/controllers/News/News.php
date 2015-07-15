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

namespace controllers\News;

use controllers\Controller as controller;
use models\News as model;


/**
 * Class News
 * @package controllers\News
 */
class News  extends controller\Controller
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
    public function news() {
        $model = new model\News();
        echo $this->mustache->render('news', $model);
    }

}