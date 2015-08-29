<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace application\Helper\Reload;

use application\Exception\ReloadException;

return
    /**
     * Reload current page please, be careful to avoid loop of reload
     * @throws ReloadException
     * @return void
     */
    function () {
        throw new ReloadException();
    };
