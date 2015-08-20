<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace validator\Exception;

use exception\BadRequestException;

/**
 * Validator Exception
 *
 * @package  validator\Exception
 */
class ValidatorException extends BadRequestException
{
    /**
     * @var string Exception message
     */
    protected $message = 'Invalid Arguments';

    /**
     * @var array Set of error messages
     */
    protected $errors = [];

    /**
     * Create and throw Exception
     * @param $key
     * @param $error
     * @return self
     */
    public static function exception($key, $error)
    {
        $exception = new self;
        $exception->setError($key, $error);
        return $exception;
    }

    /**
     * Set error by Key
     * @param string $key
     * @param string $error
     * @return void
     */
    public function setError($key, $error)
    {
        $this->errors[$key] = $error;
    }

    /**
     * Set errors
     * @param array $errors
     * @return void
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
