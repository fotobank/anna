<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace response\Presentation;

use view\View;

/**
 * Json
 *
 * @package  response\Presentation
 */
class Cli extends AbstractPresentation
{
    /**
     * Response to CLI
     */
    public function process()
    {
        // prepare body
        $body = $this->response->getBody();
        if ($body) {
            // extract data from view
            if ($body instanceof View) {
                $body = $body->toArray();
            }

            // output
            if (is_array($body)) {
                // just print to console as key-value pair
                $output = [];

                array_walk_recursive($body, function ($value, $key) use (&$output) {
                    $output[] = $key .': '. $value;
                });

                $body = implode("\n", $output);
            }
            $this->response->setBody($body . "\n");
        }
    }
}
