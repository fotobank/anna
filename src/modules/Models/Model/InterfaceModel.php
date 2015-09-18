<?php
/**
 * Класс Model
 * @created   by PhpStorm
 * @package   InterfaceModel.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     23.07.2015
 * @time:     21:51
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\Model;


/**
 * Interface InterfaceModel
 * @package modules\Models\Model
 */
interface InterfaceModel
{
    public function globalHeartMenu();
    public function getCategorii();

    /**
     * @param $text
     * @return mixed
     */
    public function esc($text);

    /**
     * @param $onluIndex
     * @return mixed
     */
    public function setOnluIndex($onluIndex);
}