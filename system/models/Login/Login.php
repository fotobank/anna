<?php
/**
 * Класс предназначен для
 *
 * @created   by PhpStorm
 * @package   Login.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
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
    // сообщение ошибки для окна логина
    public $err_login = false;

    /**
     * @param $options
     */
    public function __construct($options = [])
    {
        // настройка свойств класса
        $this->setOptions($options);
        // инициализация конструктора родительского класса
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
                    $this->err_login = ('Поле "Login" является обязательным для заполнения.');
                }
                else if(empty($_POST['password']) || $_POST['password'] == 'password')
                {
                    $this->err_login = ('Для входа необходимо вернуться и заполнить поле "Password".');
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
                            // отключаем кнопку login и включаем exit
                            $this->login = 1;
                        }
                        else
                        {
                            $this->err_login = ('Неверные логин и пароль!');
                        }
                    }
                    else
                    {
                        $this->err_login = ('Неверные логин и пароль!');

                    }
                }
            }
        }

        return true;
    }
}