<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace common\Container;

/**
 * Container implements JsonSerializable interface
 * @see JsonSerializable
 *
 *  * @method array toArray()
 *
 * @package  Common
 */
trait JsonSerialize
{
    /**
     * Specify data which should be serialized to JSON
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
