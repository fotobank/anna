<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   InterfaceModelsBase.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     23.07.2015
 * @time:     21:51
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\Base;


/**
 * Interface InterfaceModelsBase
 * @package modules\Models\Base
 */
interface InterfaceModelsBase
{

    public function getMetaTitle();
    public function globalHeartMenu();
    public function getDbTitleName();

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