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
 * Class Email
 * @package validator\Rule
 */
class Email extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a valid email address';

    /**
     * @var bool Check DNS record
     */
    protected $checkDns;

    /**
     * Setup validation rule
     * @param bool $checkDns
     */
    public function __construct($checkDns = false)
    {
        $this->checkDns = $checkDns;
    }

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        if (is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL)) {
            list(, $domain) = explode('@', $input, 2);
            if ($this->checkDns) {
                return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
