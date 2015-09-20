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
 * @time:     13:04
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\News;

use modules\Models\Model\Model;
use lib\Config\Config;

/**
 * Class News
 * @package modules\Models\News
 */
class News extends Model
{

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \Exception
     * @internal param $options
     */
    public function __construct(Config $config)
    {
        try
        {
            // настройка свойств класса
            $this->setOptions($config->getData('news'));
            // инициализация конструктора родительского класса
            parent::__construct($config);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}