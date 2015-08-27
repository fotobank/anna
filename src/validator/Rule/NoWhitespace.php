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
 * Class NoWhitespace
 * @package validator\Rule
 */
class NoWhitespace extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must not contain whitespace';

    /**
     * Check input data
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        return is_null($input) || !preg_match('/\s/', $input);
    }
}
