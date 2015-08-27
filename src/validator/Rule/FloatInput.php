<?php
/**
 * Framework Component
 *
 * @copyright Alex PHP
 *
 */

/**
 * @namespace
 */
namespace validator\Rule;

/**
 * Class Float
 * @package validator\Rule
 */
class FloatInput extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a float number';

    /**
     * Check input data
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        return is_float(filter_var($input, FILTER_VALIDATE_FLOAT));
    }
}
