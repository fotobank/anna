<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace Bluz\Application\Exception;
use exception\ApplicationException;

/**
 * NotImplemented Exception
 *
 */
class NotImplementedException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Not Implemented';

    /**
     * Not Implemented HTTP Code
     * @var int
     */
    protected $code = 501;
}
