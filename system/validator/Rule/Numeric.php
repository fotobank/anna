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
 * Class Numeric
 * @package validator\Rule
 */
class Numeric extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be numeric';

    /**
     * Check for numeric
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return is_numeric($input);
    }
}
