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
class Condition extends AbstractRule
{
    /**
     * @var bool Condition rule
     */
    protected $condition;

    /**
     * Setup validation rule
     * @param bool $condition
     */
    public function __construct($condition)
    {
        $this->condition = $condition;
    }

    /**
     * Check input data
     * @param mixed $input
     * @return bool
     */
    public function validate($input)
    {
        return (bool) $this->condition;
    }
}
