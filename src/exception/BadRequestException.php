<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace exception;

/**
 * BadRequest Exception
 *
 * @package  exception
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
