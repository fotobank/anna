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
 * Class Negative
 * @package validator\Rule
 */
class Negative extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be negative';

    /**
     * Check for negative number
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        return $input < 0;
    }
}
