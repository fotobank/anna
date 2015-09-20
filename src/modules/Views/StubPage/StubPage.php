<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   StubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     20.09.2015
 * @time:     23:28
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Views\StubPage;

use modules\Models\Model\Model;
use modules\Views\View\View;

/**
 * Class StubPage
 *
 * @package modules\Views\StubPage
 */
class StubPage extends View
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
            $this->render('stubPage', $model);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

}