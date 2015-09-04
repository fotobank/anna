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

namespace modules\Controllers\News;

use modules\Controllers\Controller\Controller;
use modules\Models\News\News as ModelNews;
use view\View;


/**
 * Class News
 * @package modules\Controllers\News
 */
class News  extends Controller
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
    public function news() {
        try
        {
            $model = new ModelNews();

            return $this->viewer->render('news', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

}