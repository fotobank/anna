<?php
/**
 * Класс HelperException
 * @created   by PhpStorm
 * @package   HelperException.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     30.07.2015
 * @time:     11:07
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace exception;
use Exception;


/**
 * Class HelperException
 * @package exception
 */
class HelperException extends CommonException
{
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
