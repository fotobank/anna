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
 * Forbidden Exception
 *
 */
class ForbiddenException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Forbidden';

    /**
     * Forbidden HTTP code
     * @var int
     */
    protected $code = 403;
}
