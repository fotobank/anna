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
     * @return void
     */
    function ($arr_param)
    {
        $controller = $arr_param[0];
        $method = array_key_exists(1, $arr_param) ? $arr_param[1] : null;

        $this->redirect($controller, $method);
    };
