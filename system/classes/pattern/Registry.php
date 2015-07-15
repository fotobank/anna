<?php
/**
 *  ласс Registry
 * @created   by PhpStorm
 * @package   Reg.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     13.07.2015
 * @time:     23:11
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace classes\pattern;


/**
 * //создать экземпл€р класса db(если он уже существует, то перезаписать) и вызвать функцию action<br>
 * Registry::build('db')->action();
 *
 * удалить экземпл€р класса db
 * Registry::del('db');
 *
 * обратитьс€ к экземпл€ру класса db(если он не существует, то создать) и вызвать функцию action
 * можно обращатьс€ из любого места в приложении, вызыватьс€ будет один и тот же экземпл€р
 * Registry::call('db')->action();
 *
 * аналогично предыдущему, но с одной разницей: экземпл€р класса db будет записан в переменную $vars с индексом db:site
 * можно создавать сколь угодно много экземпл€ров одного и того же класса с разными индексами
 * Registry::call('db:site')->action();
 *
 * удалить экземпл€р класса db с индексом site
 * Registry::del('db:site');
 *
 * и еще пара примеров:
 * Registry::build('db:site')->connect($login,$pass,$host);
 * Registry::build('db:forum')->connect($login2,$pass2,$host2);
 *
 * $row1 = Registry::call('db:site')->query("query to site database...");
 * $row2 = Registry::call('db:forum')->query("query to forum database...");
 *
 * Registry::call('tpl:style1')->setStyle('style2');
 *
 * $template1 = Registry::call('tpl:style1')->load('index')->compile(); //получить шаблон index из стил€ style1
 * $template2 = Registry::call('tpl:style2')->load('index')->compile(); //получить шаблон index из стил€ style2
 *
 * Class Registry
 * @package classes\pattern
 */
class Registry
{

    private static $vars = [];

    /**
     * @param $id
     * @param bool $data
     * @return mixed
     */
    public static function build($id, $data = false)
    {
        //создание класса
        $arr = explode(':', $id);
        $class = reset($arr);
        self::$vars[$id] = new $class($data);
        return self::$vars[$id];

    }

    /**
     * @param $id
     * @param bool $data
     * @return mixed
     */
    public static function call($id, $data = false)
    {
        //вызов класса(при отсутствии готового экземпл€ра - создание нового и вызов)
        if (!array_key_exists($id, self::$vars)) {

            return self::build($id, $data);

        } else {

            return self::$vars[$id];

        }
    }


    /**
     * @param $id
     * @return bool
     */
    public static function del($id)
    {
        //удаление значени€(любого типа, в т.ч. класса)
        if (array_key_exists($id, self::$vars)) {

            unset(self::$vars[$id]);

        }
        return true;
    }
}