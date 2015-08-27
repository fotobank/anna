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
 * Class AlphaNumeric
 * @package validator\Rule
 */
class Alpha extends AbstractCtypeRule
{
    /**
     * Check for alphabetic character(s)
     * @param string $input
     * @return bool
     */
    protected function validateClean($input)
    {
        return ctype_alpha($input);
    }

    /**
     * Get error template
     * @return string
     */
    public function getTemplate()
    {
        if (empty($this->additionalChars)) {
            return __('{{name}} must contain only letters');
        } else {
            return __('{{name}} must contain only letters and "%s"', $this->additionalChars);
        }
    }
}
