<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace application\Exception;

use exception\ApplicationException;

/**
 * Unauthorized Exception
 *
 */
class UnauthorizedException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Unauthorized';

    /**
     * Unauthorized HTTP code
     * @var int
     */
    protected $code = 401;
}
