<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace views\View\Helper\Style;

use views\View\View;

return
    /**
     * Generate HTML for <style> or <link> element
     *
     * @var View $this
     * @param string $style
     * @param string $media
     * @return string
     */
    function ($style, $media = 'all') {
        if ('.css' == substr($style, -4)) {
            if (strpos($style, 'http://') !== 0
                && strpos($style, 'https://') !== 0
            ) {
                $style = $this->baseUrl($style);
            }
            return "\t<link href=\"" . $style . "\" rel=\"stylesheet\" media=\"" . $media . "\"/>\n";
        } else {
            return "\t<style type=\"text/css\" media=\"" . $media . "\">\n"
            . $style . "\n"
            . "\t</style>";
        }
    };
