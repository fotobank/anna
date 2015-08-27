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
 * Class AbstractCtypeRule
 * @package validator\Rule
 */
abstract class AbstractCtypeRule extends AbstractFilterRule
{
    /**
     * Filter input data
     * @param string $input
     * @return string
     */
    protected function filter($input)
    {
        $input = parent::filter((string) $input);
        return preg_replace('/\s/', '', $input);
    }
}
