<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace view\Helper;

use view\View;

return
    /**
     * Return Exception message
     *
     * @var View $this
     * @param \Exception $exception
     * @return string
     */
    function ($exception) {
        if (DEBUG_MODE) {
            // @codeCoverageIgnoreStart
            // exception message for developers
            return
                '<div class="alert alert-error">' .
                '<strong>Exception</strong>: ' .
                $exception->getMessage() .
                '</div>';
            // @codeCoverageIgnoreEnd
        } else {
            return '';
        }
    };
