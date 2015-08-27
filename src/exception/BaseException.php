<?php
/**
 * Класс BaseException
 * @created   by PhpStorm
 * @package   BaseException.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     19.07.2015
 * @time:     14:33
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace exception;

use Exception;
    /**
     * Class RouteException
     */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
abstract class BaseException extends Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    /**
     * @param null       $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code, $previous);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return "<b>Message: </b><span style='text-decoration: underline; font-size: 18px;'> '{$this->message}'</span><br>" .
        "<b>File: </b>'{$this->file}'<br>" .
        "<b>Line: </b>'{$this->line}'<br>";
//        "{$this->getTraceAsString()}";
    }
}