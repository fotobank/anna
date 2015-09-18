<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Widgets.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     15.09.2015
 * @time:     0:18
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Views\Widgets;
use modules\Models\Model\Model;
use view\View;

/**
 * Class Widgets
 *
 * @package modules\Views\Widgets
 */
class Widgets extends View
{
    /**
     * @param Model $model
     *
     * @return mixed|\modules\Models\Model\Model
     */
    public function doUpdate(Model $model)
    {
        $name        = $route->dispatchValues();
        $this->addHelperPath(ROOT_PATH . strtolower($name['controller']) . '/' . $this->ucwordsKey($name['method']) . '/');
        // вызов виджета
        $this->{$name['action']}();
        exit;
    }
}