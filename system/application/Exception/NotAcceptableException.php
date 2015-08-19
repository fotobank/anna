<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace application\Exception;

use exception\ApplicationException;

/**
 * Not Acceptable Exception
 */
class NotAcceptableException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Not Acceptable';

    /**
     * Method Not Allowed
     * @var int
     */
    protected $code = 406;
}
