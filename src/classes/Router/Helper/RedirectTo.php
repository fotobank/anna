<?php
/**
 * Alex Framework Component
 */

/**
 * @namespace
 */
namespace classes\Router\Helper\RedirectTo;

use Application\Application;
use proxy\Router;

return
    /**
     * Redirect to controller
     * @var Application $this
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    function ($controller = 'index', $method = 'index', $params = []) {
        $url = Router::getUrl($controller, $method, $params);
        $this->redirect($url);
    };
