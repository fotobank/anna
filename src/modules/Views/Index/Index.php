<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     14.09.2015
 * @time:     13:13
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Views\Index;
use modules\Models\Model\Model;
use modules\Views\View\View;


/**
 * Class Index
 *
 * @package modules\Views\Index
 */
class Index extends View
{
    /**
     * @param Model $model
     *
     * @return mixed|\modules\Models\Model\Model
     * @throws \Exception
     */
    public function doUpdate(Model $model)
    {
        try
        {
            $this->render('index', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}