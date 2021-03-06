<?php
/**
 * Alex Framework Component
 *
 * @copyright Alex PHP
 *
 */

/**
 * @namespace
 */
namespace exception;

use Exception;

/**
 * Basic Exception for Alex framework
 *
 * @package  Common\Exception
 * @author   Alex Jurii
 */
class CommonException extends Exception
{
    /**
     * @var int Used as default HTTP code for exceptions
     */
    protected $code = 500;

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Construct the exception. Note: The message is NOT binary safe.
     * @link http://php.net/manual/en/exception.construct.php
     * Fix for https://github.com/facebook/hhvm/blob/HHVM-3.4.0/hphp/system/php/lang/Exception.php#L55
     *
     * @param string    $message  [optional] The Exception message to throw.
     * @param int       $code     [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        $numAgs = func_num_args();
        if ($numAgs >= 1) {
             $this->message = $message;
        }

        if ($numAgs >= 2) {
            $this->code = $code;
        }
        parent::__construct($this->message, $this->code, $previous);
    }
}
