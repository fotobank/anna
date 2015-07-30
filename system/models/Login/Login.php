<?php
/**
 * ����� ������������ ���
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
        IF(!array_key_exists('logged', $_SESSION) || $_SESSION['logged'] !== true):
            if(!array_key_exists('submit', $_POST))
            {
                return false;
            }
            else
            {

                if(empty($_POST['login']) || $_POST['login'] == 'login')
                {

                    $mess = ('���� "Login" �������� ������������ ��� ����������.');

                }
                else if(empty($_POST['pass']) || $_POST['pass'] == 'pass')
                {

                    $mess = ('��� ����� ���������� ��������� � ��������� ���� "Password".');

                }
                else
                {
                    Db::where('login', $_POST['login']);
                    $q = Db::get($GLOBALS['tbl_users'], NULL, ['id', 'login', 'pass']);

                    if(count($q) > '0')
                    {
                        $_pass = $_id = $_login = NULL;
                        extract($q[0], EXTR_PREFIX_ALL, '');

                        if(md5($_POST['pass']) === $_pass)
                        {
                            $_SESSION['logged'] = TRUE;
                            $_SESSION['id']     = $_id;
                            $_COOKIE['nick']    = $_login;
                            if($_id == 1)
                            {
                                $_SESSION['admnews'] = md5($_login . '///' . $_pass);
                            }
                            $_SESSION['nick'] = $_login;
                            main_redir('admin.php');
                        }
                        else
                        {
                            $mess = ('�������� ����� � ������!');
                        }
                    }
                    else
                    {
                        $mess = ('�������� ����� � ������!');

                    }

                }
                return true;
            }
        }

}