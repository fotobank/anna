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
     * Class routeException
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
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return '<br><b>' . get_class($this) . ":</b> '{$this->message}'<br>" .
        "<b>in File: </b>'{$this->file}'<br>" .
        "<b>Line: </b>'{$this->line}'<br>" .
        "{$this->getTraceAsString()}";
    }
}