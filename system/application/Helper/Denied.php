<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace application\Helper;

use application\Exception\ForbiddenException;

return
    /**
     * Denied helper
     * @return bool
     */
    function () {
        throw new ForbiddenException();
    };
