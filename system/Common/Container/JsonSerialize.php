<?php
/**
 * @namespace
 */
namespace common\Container;

/**
 * Container implements JsonSerializable interface
 * @see JsonSerializable
 *
 * @package  Common
 *
 * @method array toArray()
 *
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
