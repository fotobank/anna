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
 * Class Version
 * @link http://semver.org/
 * @package validator\Rule
 */
class Version extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a version';

    /**
     * Check for version format
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        $pattern = '/^[0-9]+\.[0-9]+\.[0-9]+([+-][^+-][0-9A-Za-z-.]*)?$/';

        return (bool) preg_match($pattern, $input);
    }
}
