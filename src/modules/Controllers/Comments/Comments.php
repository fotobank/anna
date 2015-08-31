<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Comments.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     13:07
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Comments;


use modules\Controllers\Controller\Controller;
use modules\Models\Comments as model;


/**
 * Class Comments
 * @package modules\Controllers\Comments
 */
class Comments  extends Controller
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
    public function comments() {
        $model = new model\Comments();
        return $this->mustache->render('comments', $model);
    }

}