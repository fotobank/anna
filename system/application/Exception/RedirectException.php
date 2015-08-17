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
 * Redirect Exception
 *
 * @package  application\Exception
 *
 */
class RedirectException extends ApplicationException
{
    /**
     * Exception message consist Location data
     * @var string
     */
    protected $message = 'Application Redirect';

    /**
     * Redirect HTTP code
     *
     * - 301 Moved Permanently
     * - 302 Moved Temporarily
     * - 307 Temporary Redirect
     *
     * @var int
     */
    protected $code = 302;
}
