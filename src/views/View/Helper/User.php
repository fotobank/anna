<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace views\View\Helper\User;

use views\View\View;
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
