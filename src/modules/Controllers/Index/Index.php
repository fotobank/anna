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
use modules\Models\Index\Index as ModelIndex;
use view\View;


/**
 * Class controller_Index
 *
 * @package modules\Controllers\Index
 */
class Index extends Controller
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
     * @throws Exception
     */
    public function index()
    {
        $options = [
            // свойства IndexPage
            'http_host'     => getenv('HTTP_HOST'),  // телефон в слайдере
            'filenews'      => 'news.txt', // файл новостей
            'lite_box_path' => 'files/slides/*.jpg' // маска и путь сканирования лайтбокса
        ];
        $model = new ModelIndex($options);

        $this->viewer->render('index', $model);
    }

    /**
     * View should be callable
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke()
    {
        $this->index();
    }

    /**
     * Render like string
     *
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        try
        {
            $this->index();
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}