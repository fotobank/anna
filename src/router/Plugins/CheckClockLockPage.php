<?php
/**
 * Plugin Alex Framework Component
 *
 * @created   by PhpStorm
 * @package   CheckClockLockPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     31.08.2015
 * @time:     7:48
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/**
 * @namespace
 */
namespace router\Plugins\CheckClockLockPage;


use proxy\Db;

return

    function () {

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

    };
