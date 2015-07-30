<?php
/**
 * Класс предназначен для
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
use classes\pattern\Proxy\Db;


/**
 * Class Login
 * @package models\Login
 */
class Login extends model\Base
{

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
        $mess = false;
        if(!array_key_exists('logged', $_SESSION) || $_SESSION['logged'] !== true)
        {
            if(!array_key_exists('submit', $_POST))
            {
                return false;
            }
            else
            {

                if(empty($_POST['login']) || $_POST['login'] == 'login')
                {

                    $mess = ('Поле "Login" является обязательным для заполнения.');

                }
                else if(empty($_POST['password']) || $_POST['password'] == 'password')
                {

                    $mess = ('Для входа необходимо вернуться и заполнить поле "Password".');

                }
                else
                {
                    Db::where('login', $_POST['login']);
                    $q = Db::get(TBL_USERS, NULL, ['id', 'login', 'pass']);

                    if(count($q) > '0')
                    {
                        $_pass = $_id = $_login = NULL;
                        extract($q[0], EXTR_PREFIX_ALL, '');

                        if(md5($_POST['password']) === $_pass)
                        {
                            $_SESSION['logged'] = TRUE;
                            $_SESSION['id']     = $_id;
                            $_COOKIE['nick']    = $_login;
                            if($_id == 1)
                            {
                                $_SESSION['admnews'] = md5($_login . '///' . $_pass);
                            }
                            $_SESSION['nick'] = $_login;
                        }
                        else
                        {
                            $mess = ('Неверные логин и пароль!');
                        }
                    }
                    else
                    {
                        $mess = ('Неверные логин и пароль!');

                    }
                }
            }
        }
        return $mess;
    }
}