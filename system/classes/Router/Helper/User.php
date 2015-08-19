<?php
/**
 * Alex Framework Component
 */

/**
 * @namespace
 */
namespace Application\Helper;

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
