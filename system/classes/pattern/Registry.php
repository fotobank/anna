<?php
/**
 * ����� Registry
 * @created   by PhpStorm
 * @package   Reg.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     13.07.2015
 * @time:     23:11
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace classes\pattern;


/**
 * //������� ��������� ������ db(���� �� ��� ����������, �� ������������) � ������� ������� action<br>
 * Registry::build('db')->action();
 *
 * ������� ��������� ������ db
 * Registry::del('db');
 *
 * ���������� � ���������� ������ db(���� �� �� ����������, �� �������) � ������� ������� action
 * ����� ���������� �� ������ ����� � ����������, ���������� ����� ���� � ��� �� ���������
 * Registry::call('db')->action();
 *
 * ���������� �����������, �� � ����� ��������: ��������� ������ db ����� ������� � ���������� $vars � �������� db:site
 * ����� ��������� ����� ������ ����� ����������� ������ � ���� �� ������ � ������� ���������
 * Registry::call('db:site')->action();
 *
 * ������� ��������� ������ db � �������� site
 * Registry::del('db:site');
 *
 * � ��� ���� ��������:
 * Registry::build('db:site')->connect($login,$pass,$host);
 * Registry::build('db:forum')->connect($login2,$pass2,$host2);
 *
 * $row1 = Registry::call('db:site')->query("query to site database...");
 * $row2 = Registry::call('db:forum')->query("query to forum database...");
 *
 * Registry::call('tpl:style1')->setStyle('style2');
 *
 * $template1 = Registry::call('tpl:style1')->load('index')->compile(); //�������� ������ index �� ����� style1
 * $template2 = Registry::call('tpl:style2')->load('index')->compile(); //�������� ������ index �� ����� style2
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
        //�������� ������
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
        //����� ������(��� ���������� �������� ���������� - �������� ������ � �����)
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
        //�������� ��������(������ ����, � �.�. ������)
        if (array_key_exists($id, self::$vars)) {

            unset(self::$vars[$id]);

        }
        return true;
    }
}