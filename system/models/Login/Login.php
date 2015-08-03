<?php
/**
 * ����� ������������ ���
 *
 * @created   by PhpStorm
 * @package   Login.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     26.07.2015
 * @time:     0:46
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\Login;

use models\Base as model;
use proxy\Db;
use proxy\Session;


/**
 * Class Login
 *
 * @package models\Login
 */
class Login extends model\Base
{
    // ��������� ������ ��� ���� ������
    public $err_login = false;

    /**
     * @param $options
     */
    public function __construct($options = [])
    {
        // ��������� ������� ������
        $this->setOptions($options);
        // ������������� ������������ ������������� ������
        parent::__construct();

    }

    /**
     * @return bool
     */
    public function login()
    {
        if(Session::has('logged'))
        {
            if( ! array_key_exists('submit', $_POST))
            {
                return false;
            }
            else
            {
                if(empty($_POST['login']) || $_POST['login'] == 'login')
                {
                    $this->err_login = ('���� "Login" �������� ������������ ��� ����������.');
                }
                else if(empty($_POST['password']) || $_POST['password'] == 'password')
                {
                    $this->err_login = ('��� ����� ���������� ��������� � ��������� ���� "Password".');
                }
                else
                {
                    Db::where('login', $_POST['login']);
                    $q = Db::get(TBL_USERS, null, ['id', 'login', 'pass']);

                    if(count($q) > '0')
                    {
                        $_pass = $_id = $_login = null;
                        extract($q[0], EXTR_PREFIX_ALL, '');

                        if(md5($_POST['password']) === $_pass)
                        {
                            Session::set('logged', true);
                            Session::set('id', $_id);
                            $_COOKIE['nick'] = $_login;
                            if($_id == 1)
                            {
                                Session::set('admnews', md5($_login . '///' . $_pass));
                            }
                            Session::set('nick', $_login);
                            // ��������� ������ login � �������� exit
                            $this->login = 1;
                        }
                        else
                        {
                            $this->err_login = ('�������� ����� � ������!');
                        }
                    }
                    else
                    {
                        $this->err_login = ('�������� ����� � ������!');

                    }
                }
            }
        }

        return true;
    }
}