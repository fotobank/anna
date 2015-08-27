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
 * Class String
 * @package validator\Rule
 */
class StringInput extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a string';

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return is_string($input);
    }
}
