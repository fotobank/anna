<?php
/**
 * Alex Framework Component
 */

/**
 * @namespace
 */
namespace Application\Helper;

use Application\Exception\ReloadException;

return
    /**
     * Reload current page please, be careful to avoid loop of reload
     * @throws ReloadException
     * @return void
     */
    function () {
        throw new ReloadException();
    };
