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
 * Class Integer
 * @package validator\Rule
 */
class Integer extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be an integer number';

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return is_numeric($input) && (int) $input == $input;
    }
}
