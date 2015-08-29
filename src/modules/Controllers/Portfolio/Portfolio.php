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
use modules\Models\Portfolio as model;


/**
 * Class Portfolio
 * @package modules\Controllers\Portfolio
 */
class Portfolio  extends Controller
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
    public function portfolio() {
        $model = new model\Portfolio();
        echo $this->mustache->render('portfolio\portfolio', $model);
    }

}