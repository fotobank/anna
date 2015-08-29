<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace view\Helper\Checkbox;

use view\View;

return
    /**
     * Generate HTML for <input type="checkbox">
     *
     *
     * @var View $this
     * @param string $name
     * @param string|null $value
     * @param bool $checked
     * @param array $attributes
     * @return string
     */
    function ($name, $value = null, $checked = false, array $attributes = []) {
        /** @var View $this */
        if (true === $checked) {
            $attributes['checked'] = 'checked';
        } elseif (false !== $checked && ($checked == $value)) {
            $attributes['checked'] = 'checked';
        }

        if (null !== $value) {
            $attributes['value'] = $value;
        }

        $attributes['name'] = $name;
        $attributes['type'] = 'checkbox';

        return '<input ' . $this->attributes($attributes) . '/>';
    };
