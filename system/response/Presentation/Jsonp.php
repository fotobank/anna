<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace response\Presentation;

use proxy\Request;

/**
 * JSONP presentation
 *
 * @package  Response\Presentation
 *
 * @author   Alex Jurii
 * @created  17.11.2014 13:47
 */
class Jsonp extends AbstractPresentation
{
    /**
     * Response as JSONP
     */
    public function process()
    {
        // override response code so javascript can process it
        $this->response->setHeader('Content-Type', 'application/javascript');

        // prepare body
        $body = $this->response->getBody();
        if ($body) {
            // convert to JSON
            $body = json_encode($body);

            // try to guess callback function name
            //  - check `callback` param
            //  - check `jsonp` param
            //  - use `callback` as default callback name
            $callback = Request::getParam('jsonp', Request::getParam('callback', 'callback'));
            $body = $callback .'('. $body .')';

            // setup content length
            $this->response->setHeader('Content-Length', strlen($body));

            // prepare to JSON output
            $this->response->setBody($body);
        }
    }
}
