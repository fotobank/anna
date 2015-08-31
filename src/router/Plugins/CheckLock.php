<?php
/**
 * Plugin Alex Framework Component
 * ����������� �������� - ��������
 *
 * @created   by PhpStorm
 * @package   CheckClockLockPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
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

    function ()
    {
        Db::where('url', $this->current_controller);
        $lock = Db::getOne('lock_page');
        assert('$lock', '� ���� ��� ������� � "LockPage" ����������� "' . $this->current_controller . '"');

        $difference_time = $lock['end_date'] - time();
        /** ���� ����� ������� �� ����� ��� ���� ����� �� �� ������� ��������� - ���������� �������� �������� */
        if($difference_time > 0 || ($difference_time < 0 && $lock['auto_run'] !== 1))
        {
            $this->current_controller = $lock['controller'];
            $this->current_method     = $lock['method'];
        }
    };
