<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace http;

use proxy\Request as ProxyRequest;
use response\AbstractResponse;

/**
 * Response
 *
 * @category http
 * @package  response
 */
class Response extends AbstractResponse
{
    /**
     * Send headers
     *
     * HTTP does not define any limit
     * However most web servers do limit size of headers they accept.
     * For example in Apache default limit is 8KB, in IIS it's 16K.
     * Server will return 413 Entity Too Large error if headers size exceeds that limit
     *
     * @return void
     */
    protected function sendHeaders()
    {
        // setup response code
        http_response_code($this->code);

        // send stored cookies
        foreach ($this->cookies as $cookie) {
            setcookie(array_values($cookie));
        }

        // send stored headers
        foreach ($this->headers as $key => $value) {
            header($key .': '. implode(', ', $value));
        }
    }

    /**
     * Send body
     * @return void
     */
    protected function sendBody()
    {
        // Nobody for HEAD and OPTIONS
        if (Request::METHOD_HEAD == ProxyRequest::getMethod()
            || Request::METHOD_OPTIONS == ProxyRequest::getMethod()) {
            return;
        };

        // Body can be Closures
        $content = $this->body;
        if ($content instanceof \Closure) {
            $content();
        } else {
            echo $content;
        }
    }
}
