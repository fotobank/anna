<?php
/**
 * Alex Framework Component
 *

 */

/**
 * @namespace
 */
namespace router\Helper\Redirect;

use Application\Exception\RedirectException;

return
    /**
     * Redirect to url
     * @param string $url
     * @throws RedirectException
     * @return void
     */
    function ($url) {
        throw new RedirectException($url);
    };
