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
 * Class Positive
 * @package validator\Rule
 */
class Positive extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be positive';

    /**
     * Check for positive number
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        return $input > 0;
    }
}
