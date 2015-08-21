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
        if(is_array($url))
        {
            $url = (implode('/', $url));
        }
        throw new RedirectException($url, 302);
    };
