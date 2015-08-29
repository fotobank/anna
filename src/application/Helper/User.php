<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace application\Helper\User;

use application\Application;
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
