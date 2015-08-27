<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace piew\Helper;

use view\View;
use proxy\Auth;

return
    /**
     * Get current user
     *
     * @var View $this
     * @return \Auth\AbstractRowEntity|null
     */
    function () {
        return Auth::getIdentity();
    };
