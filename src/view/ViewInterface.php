<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace view;

/**
 * ViewInterface
 *
 * @package  View
 */
interface ViewInterface
{
    /**
     * Setup path to templates
     *
     * Example of usage
     *     $view->setPath('/modules/users/views');
     *
     * @param string $path
     * @return ViewInterface
     */
    public function setPath($path);

    /**
     * Setup template
     *
     * Example of usage
     *     $view->setTemplate('index.phtml');
     *
     * @param string $file
     * @return ViewInterface
     */
    public function setTemplate($file);

    /**
     * Merge data from array
     *
     * @param array $data
     * @return ViewInterface
     */
    public function setFromArray(array $data);

    /**
     * Get data as array
     *
     * @return array
     */
    public function toArray();
}
