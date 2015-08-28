<?php
/**
 * Alex Framework Component
 *

 */

/**
 * @namespace
 */
namespace classes\Router\Helper\Denied;

use Application\Exception\ForbiddenException;

return
    /**
     * Denied helper can be declared inside Bootstrap
     * @return bool
     */
    function () {
        throw new ForbiddenException();
    };
