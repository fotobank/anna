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
 * Class Required
 * @package validator\Rule
 */
class Required extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} is required';

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        if (is_string($input)) {
            $input = trim($input);
        }

        return (false !== $input) && (null !== $input) && ('' !== $input);
    }
}
