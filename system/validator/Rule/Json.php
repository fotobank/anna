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
 * Class Json
 * @package validator\Rule
 */
class Json extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a valid JSON string';

    /**
     * Check for valid JSON string
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        return (bool) (json_decode($input));
    }
}
