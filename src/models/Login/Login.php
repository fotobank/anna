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
    // ��������� ������ ��� ���� ������
    public $err_login = '';

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
                    $this->err_login = ('���� "Login" �������� ������������ ��� ����������.');
                    return false;
                }
                else if(Post::get('password') == 'password')
                {
                    $this->err_login = ('��� ����� ���������� ��������� � ��������� ���� "Password".');
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
                            // ��������� ������ login � �������� exit
                            $this->login = true;
                            return true;
                        }
                    }
                }
            }
            $this->err_login = ('�������� ����� � ������!');
            return false;
        }
        return false;
    }
}