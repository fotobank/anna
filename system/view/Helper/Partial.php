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
use view\ViewException;

return
    /**
     * Render partial file
     *
     * be careful, method rewrites the View variables with params
     *
     * @var View $this
     * @param string $__template
     * @param array $__params
     * @throws ViewException
     * @return string
     */
    function ($__template, $__params = []) {
        $__file = null;
        if (file_exists($this->path . '/' . $__template)) {
            $__file = $this->path . '/' . $__template;
        } else {
            foreach ($this->partialPath as $__path) {
                if (file_exists($__path . '/' . $__template)) {
                    $__file = $__path . '/' . $__template;
                    break;
                }
            }
        }

        if (null === ($__file)) {
            throw new ViewException("Template '{$__template}' not found");
        }

        if (count($__params)) {
            extract($__params);
        }
        unset($__params);

        ob_start();
        try {
            require $__file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw new ViewException("Template '{$__template}' throw exception: ".$e->getMessage());
        }
        return ob_get_clean();
    };
