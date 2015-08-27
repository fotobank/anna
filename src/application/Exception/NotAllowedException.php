<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace Application\Exception;
use exception\ApplicationException;

/**
 * Not Allowed Exception
 *
 */
class NotAllowedException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Method Not Allowed';

    /**
     * Method Not Allowed
     * @var int
     */
    protected $code = 405;
}
