<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   About.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     15.09.2015
 * @time:     23:09
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Views\About;
use modules\Models\Model\Model;
use modules\Views\View\View;


/**
 * Class About
 *
 * @package modules\Views\About
 */
class About extends View
{
    /**
     * @param Model $model
     *
     * @return mixed|\modules\Models\Model\Model|void
     * @throws \Exception
     */
    public function doUpdate(Model $model)
    {
        try
        {
            $this->render('about', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}