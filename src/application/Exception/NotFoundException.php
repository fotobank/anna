<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace Application\Exception;
use exception\ApplicationException;

/**
 * NotFound Exception
 */
class NotFoundException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Page Not Found';

    /**
     * Not Found HTTP code
     * @var int
     */
    protected $code = 404;
}
