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
 * Class Callback
 * @package validator\Rule
 */
class Callback extends AbstractRule
{
    /**
     * @var callable Callback for check input
     */
    protected $callback;

    /**
     * Setup validation rule
     * @param callable $callback
     * @throws \validator\Exception\ComponentException
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new ComponentException('Invalid callback function');
        }

        $this->callback = $callback;
    }

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return (bool) call_user_func($this->callback, $input);
    }
}
