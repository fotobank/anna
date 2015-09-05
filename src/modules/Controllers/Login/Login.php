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

namespace modules\Controllers\Login;

use modules\Controllers\Controller\Controller;
use modules\Models\Login as model;
use view\View;

/**
 * Class Login
 * @package modules\Controllers\Login
 */
class Login extends Controller
{

    /**
     * инициализация вьювера
     *
     * @param \view\View $view
     *
     */
    public function __construct(View $view)
    {
        $this->viewer = $view;
    }

    /**
     * экшен
     */
    public function userLogin()
    {
        try
        {
            $model = new model\Login();
            if($model->login())
            {
                echo $this->viewer->render('admin\admin', $model);
            }
            else
            {
                echo $this->viewer->render('admin\login', $model);
            }
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    public function userExit()
    {
        if((array_key_exists('url', $_GET) && $_GET['url'] === 'exit') ||
           !array_key_exists('logged', $_SESSION) || $_SESSION['logged'] !== true)
        {
            unset($_SESSION['id'], $_SESSION['logged'], $_SESSION['nick'], $_COOKIE['nick'], $_COOKIE['admnews']);

            header('Location: ' . $_SERVER['HTTP_REFERER'], true, 302);
        }
    }

}