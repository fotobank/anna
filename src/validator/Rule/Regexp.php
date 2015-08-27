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
 * Class Regexp
 * @package validator\Rule
 */
class Regexp extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must validate with regular expression rule';

    /**
     * @var string Regular expression for check string
     */
    protected $regexp;

    /**
     * Check string by regular expression
     * @param string $regexp
     */
    public function __construct($regexp)
    {
        $this->regexp = $regexp;
    }

    /**
     * Check string by regular expression
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return (bool) preg_match($this->regexp, $input);
    }
}
