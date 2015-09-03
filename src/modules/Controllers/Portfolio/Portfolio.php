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
use modules\Models\Portfolio\Portfolio as ModelPortfolio;
use view\View;

/**
 * Class Portfolio
 * @package modules\Controllers\Portfolio
 */
class Portfolio  extends Controller
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
    public function portfolio() {
        try
        {
            $model = new ModelPortfolio();

            return $this->viewer->render('portfolio\portfolio', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

}