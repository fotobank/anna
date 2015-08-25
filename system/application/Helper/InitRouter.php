<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     22.08.2015
 * @time:     0:01
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/**
 * @namespace
 */
namespace application\Helper;

use application\Application;
use proxy\Router;
use classes\Router\Router as MainRouter;

return
    /**
     * Get Router
     * @var Application $this
     * @return MainRouter
     */
    function () {
        Router::start();
        return Router::getInstance();
    };