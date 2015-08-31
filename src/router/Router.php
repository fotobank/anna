<?php

namespace router;

/**
 * Класс Router
 *
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     1:15
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use lib\Config\Config;
use Exception;
use proxy\Db;


/**
 * Class Router
 *
 */
class Router extends AbstractRouter
{

    /**
     * @param \lib\Config\Config|\lib\Config\Config $config
     *
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->addPluginsPath(__DIR__ . '/Plugins/');


        // проверка на блокировку url страницы
        $this->checkLockPage();

        $this->createInstance();
    }


    /**
     * Creating new instance that required by URL.
     */
    protected function createInstance()
    {
        $controller = $this->current_controller;
        $method = $this->current_method;
        $controller_path = 'modules\Controllers\\' . $controller . '\\' . $controller;
        $instance   = new $controller_path;
        // тесты
        assert('method_exists($instance, $method)', "метод '$method' не найден в контроллере '$controller_path'");
        assert('$reflection = new \ReflectionMethod($instance, $method);');
        assert('$reflection->isPublic()', "метод '$method' не является публичным в контроллере '$controller_path'");
        assert('unset($reflection);');

        $instance->$this->current_method($this->id, $this->param);
    }

    /**
     *  проверка времени блокировки страницы
     *
     * @return array
     * @internal param $url
     */
    protected function checkClockLockPage1()
    {
        Db::where('url', $this->current_controller);
        $lock = Db::getOne('lock_page');
        assert('$lock', 'в базе нет записей о "LockPage" контроллера "'.$this->current_controller.'"');

        if(null !== count($lock)) {
            $difference_time = $lock['end_date'] - time();
            if($difference_time > 0) {
                // если время таймера не вышло показывать страницу - заглушку
                return $lock;
            }
            if($lock['auto_run'] === 1) {
                // загрузить обычную страницу
                return false;
            } else {
                return $lock;
            }
        }
        // если записи нет загрузить обычную страницу
        return false;
    }


    /**
     * проверяем страницу на блокировку
     *
     * @return bool
     * @throws \Exception
     * @internal param \modules\Models\StubPage\TableStubPage $lock
     *
     */
    protected function checkLockPage()
    {
        try
        {
            $lock_page = $this->checkClockLockPage();
            if(false !== $lock_page && count($lock_page) > 0)
            {
                $this->current_controller = $lock_page['controller'];
                $this->current_method     = $lock_page['method'];

                return false;
            }

            return true;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

}