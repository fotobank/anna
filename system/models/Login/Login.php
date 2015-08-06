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
use proxy\Cookie;
use proxy\Db;
use proxy\Post;
use proxy\Session;


/**
 * Class Login
 *
 * @package models\Login
 */
class Login extends model\Base
{
    // сообщение ошибки для окна логина
    public $err_login = '';

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
        if(!Session::has('logged'))
        {
            if(!Post::has('submit'))
            {
                return false;
            }
            else
            {
                if(Post::get('login') == '')
                {
                    $this->err_login = ('Поле "Login" является обязательным для заполнения.');
                    return false;
                }
                else if(Post::get('password') == 'password')
                {
                    $this->err_login = ('Для входа необходимо вернуться и заполнить поле "Password".');
                    return false;
                }
                else
                {
                    Db::where('login', Post::get('login'));
                    $q = Db::get(TBL_USERS, null, ['id', 'login', 'pass']);

                    if(array_key_exists(0, $q))
                    {
                        $_pass = $_id = $_login = null;
                        extract($q[0], EXTR_PREFIX_ALL, '');

                        $pass = password_hash(Post::get('password'), PASSWORD_BCRYPT, ['salt' => SALT]);

                        if(password_verify(Post::get('password'), $_pass))
                        {
                            Session::set('logged', true);
                            Session::set('id', $_id);
                            Cookie::set('nick', $_login, time()+3600);
                            if($_id == 1)
                            {
                                Session::set('admnews', md5($_login . '///' . $_pass));
                            }
                            Session::set('nick', $_login);
                            // отключаем кнопку login и включаем exit
                            $this->login = true;
                            return true;
                        }
                    }
                }
            }
            $this->err_login = ('Неверные логин и пароль!');
            return false;
        }
        return false;
    }
}