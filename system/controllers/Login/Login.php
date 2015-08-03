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

namespace controllers\Login;

use controllers\Controller\Controller;
use models\Login as model;


/**
 * Class Login
 * @package controllers\Login
 */
class Login extends Controller
{

    /**
     * инициализация вьювера Mustache
     */
    public function __construct()
    {
        parent::init();
    }

    /**
     * экшен
     */
    public function userLogin()
    {
        $model = new model\Login();

        if($model->login())
        {
            echo $this->mustache->render('admin\admin', $model);
        }
        else
        {
            echo $this->mustache->render('admin\login', $model);
        }
    }

    public function userExit()
    {
        if((array_key_exists('url', $_GET) && $_GET['url'] === 'exit') ||
           !array_key_exists('logged', $_SESSION) || $_SESSION['logged'] !== true)
        {
            unset($_SESSION['id'], $_SESSION['logged'], $_SESSION['nick'], $_COOKIE['nick']);
            if(array_key_exists('admnews', $_SESSION))
            {
                unset($_SESSION['admnews']);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER'], true, 302);
        }
    }

}