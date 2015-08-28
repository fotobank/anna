<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace views\View\Helper\Script;

use views\View\View;

return
    /**
     *  Generate HTML for <script> element
     *
     * @var View $this
     * @param string $script
     * @return string
     */
    function ($script) {
        if ('.js' == substr($script, -3)) {
            if (strpos($script, 'http://') !== 0
                && strpos($script, 'https://') !== 0
                && strpos($script, '//') !== 0
            ) {
                $script = $this->baseUrl($script);
            }
            return "\t<script src=\"" . $script . "\"></script>\n";
        } else {
            return "\t<script type=\"text/javascript\">\n"
            . "\t\t<!--\n\t\t"
            . $script . "\n"
            . "\t\t//-->\n"
            . "\t</script>";
        }
    };
