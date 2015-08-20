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

use validator\Exception\ComponentException;

/**
 * Class ArrayInput
 * @package validator\Rule
 */
class ArrayInput extends AbstractRule
{
    /**
     * @var string Error template
     */
    protected $template = '{{name}} must be a array';

    /**
     * @var callable Callback for check input array
     */
    protected $callback;

    /**
     * Setup validation rule
     * @param callable $callback
     * @throws ComponentException
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new ComponentException(
                __('"%s" is not a valid callable structure', $callback)
            );
        }

        $this->callback = $callback;
    }

    /**
     * Check input data
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        if (!is_array($input)) {
            return false;
        }
        $filtered = array_filter($input, $this->callback);
        return sizeof($input) == sizeof($filtered);
    }
}
