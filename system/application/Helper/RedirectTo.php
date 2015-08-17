<?php
/**
 * BFramework Component
 */

/**
 * @namespace
 */
namespace application\Helper;

use application\Application;

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
        $this->redirect($controller, $method, $params);
    };
