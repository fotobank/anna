<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace response\Presentation;

use proxy\Messages;

/**
 * Json
 *
 * @package  Response\Presentation
 *
 * @author   Alex Jurii
 * @created  10.11.2014 18:03
 */
class Json extends AbstractPresentation
{
    /**
     * Response as Json
     */
    public function process()
    {
        // override response code so javascript can process it
        $this->response->setHeader('Content-Type', 'application/json');

        // setup messages
        if (Messages::count()) {
            $this->response->setHeader('Alex-Notify', json_encode(Messages::popAll()));
        }

        // prepare body
        $body = $this->response->getBody();
        if ($body) {
            // convert to JSON
            $body = json_encode($body);

            // setup content length
            $this->response->setHeader('Content-Length', strlen($body));

            // prepare to JSON output
            $this->response->setBody($body);
        }
    }
}
