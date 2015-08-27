<?php
/**
 * Класс IException
 * @created   by PhpStorm
 * @package   IException.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     19.07.2015
 * @time:     14:34
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


namespace exception;

use Exception;
    /**
     * Interface IException
     */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
interface IException
{
    /* Защищенные методы, унаследованные от класса Exception */
    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace

    /* Переписываемыми методы, унаследованные от класса Exception */
    public function __toString();                 // formated string for display

    /**
     * @param null|string $message
     * @param int         $code
     * @param \Exception  $previous
     */
    public function __construct($message = '', $code = 0, Exception $previous = null);
}