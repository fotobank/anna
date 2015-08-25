<?php
/**
 * Alex Framework Component
 *

 */

/**
 * @namespace
 */
namespace Application\Helper;

use Application\Exception\ForbiddenException;

return
    /**
     * Denied helper can be declared inside Bootstrap
     * @return bool
     */
    function () {
        throw new ForbiddenException();
    };
