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
class Latin extends AbstractFilterRule
{
    /**
     * Check for latin character(s)
     * @param mixed $input
     * @return bool
     */
    public function validateClean($input)
    {
        return (bool) preg_match('/^[a-z]+$/i', $input);
    }

    /**
     * Get error template
     * @return string
     */
    public function getTemplate()
    {
        if (empty($this->additionalChars)) {
            return __('{{name}} must contain only Latin letters');
        } else {
            return __('{{name}} must contain only Latin letters and "%s"', $this->additionalChars);
        }
    }
}
