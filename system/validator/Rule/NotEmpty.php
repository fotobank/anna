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
 * Class NotEmpty
 * @package validator\Rule
 */
class NotEmpty extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must not be empty';

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

        return !empty($input);
    }
}
