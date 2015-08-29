<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace view\Helper\HeadScript;


return
    /**
     * Set or generate <script> code for <head>
     *
     * @param string $script
     * @return string|null
     */
    function ($script = null) {

            // it's just alias to script() call
            return $this->script($script);
    };
