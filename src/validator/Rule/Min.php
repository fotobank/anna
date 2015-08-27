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
 * Class Min
 * @package validator\Rule
 */
class Min extends AbstractCompareRule
{
    /**
     * @var numeric Minimum value
     */
    protected $minValue;

    /**
     * Setup validation rule
     * @param numeric $minValue
     * @param bool $inclusive
     */
    public function __construct($minValue, $inclusive = false)
    {
        $this->minValue = $minValue;
        $this->inclusive = $inclusive;
    }

    /**
     * Check input data
     * @param numeric $input
     * @return bool
     */
    public function validate($input)
    {
        return $this->less($this->minValue, $input);
    }

    /**
     * Get error template
     * @return string
     */
    public function getTemplate()
    {
        if ($this->inclusive) {
            return __('{{name}} must be greater than or equals "%s"', $this->minValue);
        } else {
            return __('{{name}} must be greater than "%s"', $this->minValue);
        }
    }
}
