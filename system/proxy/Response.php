<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use exception\ComponentException;
use response\AbstractResponse as Instance;
use view\View;

/**
 * Proxy to Response
 *
 * Example of usage
 *     use Bluz\Proxy\Response;
 *
 *     Response::setStatusCode(304);
 *     Response::setHeader('Location', '/index/index');
 *
 * @package  Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static void send()
 * @see      response\AbstractResponse::send()
 *
 * @method   static string getProtocolVersion()
 * @see      response\AbstractResponse::getProtocolVersion()
 *
 * @method   static string getStatusCode()
 * @see      response\AbstractResponse::getStatusCode()
 * @method   static void  setStatusCode($code)
 * @see      response\AbstractResponse::setStatusCode()
 *
 * @method   static void  setReasonPhrase($phrase)
 * @see      response\AbstractResponse::setReasonPhrase()
 * @method   static string getReasonPhrase()
 * @see      response\AbstractResponse::getReasonPhrase()
 *
 * @method   static string getHeader($header)
 * @see      response\AbstractResponse::getHeader()
 * @method   static array  getHeaderAsArray($header)
 * @see      response\AbstractResponse::getHeaderAsArray()
 * @method   static bool   hasHeader($header)
 * @see      response\AbstractResponse::hasHeader()
 * @method   static void   setHeader($header, $value)
 * @see      response\AbstractResponse::setHeader()
 * @method   static void   addHeader($header, $value)
 * @see      response\AbstractResponse::addHeader()
 * @method   static void   removeHeader($header)
 * @see      response\AbstractResponse::removeHeader()
 *
 * @method   static array  getHeaders()
 * @see      response\AbstractResponse::getHeaders()
 * @method   static void   setHeaders(array $headers)
 * @see      response\AbstractResponse::setHeaders()
 * @method   static void   addHeaders(array $headers)
 * @see      response\AbstractResponse::addHeaders()
 * @method   static void   removeHeaders()
 * @see      response\AbstractResponse::removeHeaders()
 *
 * @method   static void  setBody($phrase)
 * @see      response\AbstractResponse::setBody()
 * @method   static View  getBody()
 * @see      response\AbstractResponse::getBody()
 * @method   static void  clearBody()
 * @see      response\AbstractResponse::clearBody()
 *
 * @method   static void setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $s = null, $h = null)
 * @see      response\AbstractResponse::setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null,
 *              $secure = null, $httpOnly = null)
 * @method   static array getCookie()
 * @see      response\AbstractResponse::getCookie()
 *
 * @method   static void  setException($exception)
 * @see      response\AbstractResponse::setException()
 * @method   static \Exception getException()
 * @see      response\AbstractResponse::getException()
 *
 * @method   static void  setPresentation($presentation)
 * @see      response\AbstractResponse::setPresentation()
 * @method   static \Exception getPresentation()
 * @see      response\AbstractResponse::getPresentation()
 *
 */
class Response extends AbstractProxy
{
    /**
     * Init instance
     *
     * @throws ComponentException
     * @return Instance
     */
    protected static function initInstance()
    {
        throw new ComponentException("Class `Proxy\\Request` required external initialization");
    }
}
