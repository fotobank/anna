<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace response\Presentation;

use response\AbstractResponse;

/**
 * AbstractPresentation
 *
 * @package  response\Presentation
 */
abstract class AbstractPresentation
{
    /**
     * @var AbstractResponse Instance
     */
    protected $response;

    /**
     * Create instance
     * @param AbstractResponse $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Process for change response
     *
     * @return void
     */
    abstract public function process();
}
