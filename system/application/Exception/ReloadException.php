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
 * Reload Exception
 *
 * @package  Application\Exception
 *
 * @author   Alex Jurii
 * @created  23.01.13 17:40
 */
class ReloadException extends ApplicationException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Application Reload';

    /**
     * HTTP OK
     * @var int
     */
    protected $code = 200;
}
