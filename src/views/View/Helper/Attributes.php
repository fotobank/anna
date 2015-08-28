<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace views\View\Helper\Attributes;

use views\View\View;

return
    /**
     * Generate HTML attributes
     *
     * @var View $this
     * @param array $attributes
     * @return string
     */
    function (array $attributes = []) {
        if (0 === count($attributes)) {
            return '';
        }
        $result = [];
        foreach ($attributes as $key => $value) {
            if (null === $value) {
                // skip empty values
                //  input: [attribute=>null]
                //  output: ''
                continue;
            }
            if (is_int($key)) {
                // allow non-associative keys
                //  input: [checked]
                //  output: 'checked="checked"'
                $key = $value;
            }
            $result[] = $key . '="' . htmlspecialchars((string)$value, ENT_QUOTES) . '"';
        }

        return implode(' ', $result);
    };
