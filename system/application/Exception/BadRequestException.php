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
 * BadRequest Exception
 *
 */
class BadRequestException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Bad Request';

    /**
     * Bad Request HTTP Code
     * @var int
     */
    protected $code = 400;
}
