<?php
/**
 * Alex Framework Component
 */

/**
 * @namespace
 */
namespace classes\Router\Helper\User;

use Application\Application;
use proxy\Auth;

return
    /**
     * Get current user
     * @var Application $this
     * @return \Auth\AbstractRowEntity|null
     */
    function () {
        return Auth::getIdentity();
    };
