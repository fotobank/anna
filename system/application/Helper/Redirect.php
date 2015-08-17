<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace application\Helper;

use application\Exception\RedirectException;

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
