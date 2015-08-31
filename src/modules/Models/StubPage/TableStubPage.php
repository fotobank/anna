<?php
/**
 * Класс предназначен для
 *
 * @created   by PhpStorm
 * @package   TableStubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     30.08.2015
 * @time:     2:10
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\StubPage;


use proxy\Db;
use router\Router;

/**
 * Class TableStubPage
 *
 * @package modules\Models\StubPage
 */
class TableStubPage
{
    protected $lock_page;

    /**
     *  проверка времени блокировки страницы
     *
     * @param \router\Router $router
     *
     * @return array
     * @internal param $url
     */
    public function checkClockLockPage(Router $router)
    {
        $controller = $router->getCurrentController();
        Db::where('url', $controller);
        $lock = Db::getOne('lock_page');

        if(null !== count($lock)) {
            $difference_time = $lock['end_date'] - time();
            if($difference_time > 0) {
                // если время таймера не вышло показывать страницу - заглушку
                $this->lock_page = $lock;
            }
            if($lock['auto_run'] === 1) {
                // загрузить обычную страницу
                $this->lock_page =  false;
            } else {
                $this->lock_page = $lock;
            }
        }
        // если записи нет загрузить обычную страницу
        $this->lock_page = false;
    }

    /**
     * @return mixed
     */
    public function getLockPage()
    {
        return $this->lock_page;
    }
}