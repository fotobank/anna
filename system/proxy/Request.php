<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use exception\ComponentException;
use http\FileUpload;
use request\AbstractRequest as Instance;

/**
 * Proxy to Request
 *
 * Example of usage
 *     use proxy\Request;
 *
 *     Request::getParam('foo');
 *
 * @package  Bluz\Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static string getMethod()
 * @see      request\AbstractRequest::getMethod()
 * @method   static void   setMethod($method)
 * @see      request\AbstractRequest::setMethod()
 *
 * @method   static string getBaseUrl()
 * @see      Bluz\Cli\Request::getBaseUrl()
 * @see      Bluz\Http\Request::getBaseUrl()
 * @method   static void   setBaseUrl($baseUrl)
 * @see      request\AbstractRequest::setBaseUrl()
 *
 * @method   static string getRequestUri()
 * @see      request\AbstractRequest::getRequestUri()
 * @method   static void   setRequestUri($requestUri)
 * @see      request\AbstractRequest::setRequestUri()
 *
 * @method   static string getCleanUri()
 * @see      request\AbstractRequest::getCleanUri()
 *
 * @method   static mixed getParam($key, $default = null)
 * @see      request\AbstractRequest::getParam()
 * @method   static void  setParam($key, $value)
 * @see      request\AbstractRequest::setParam()
 * @method   static array getParams()
 * @see      request\AbstractRequest::getParams()
 * @method   static array getAllParams()
 * @see      request\AbstractRequest::getAllParams()
 * @method   static void  setParams(array $params)
 * @see      request\AbstractRequest::setParams()
 * @method   static array getRawParams()
 * @see      request\AbstractRequest::getRawParams()
 * @method   static void  setRawParams(array $params)
 * @see      request\AbstractRequest::setRawParams()
 *
 * @method   static bool isCli()
 * @see      request\AbstractRequest::isCli()
 * @method   static bool isHttp()
 * @see      request\AbstractRequest::isHttp()
 * @method   static bool isGet()
 * @see      request\AbstractRequest::isGet()
 * @method   static bool isPost()
 * @see      request\AbstractRequest::isPost()
 * @method   static bool isPut()
 * @see      request\AbstractRequest::isPut()
 * @method   static bool isDelete()
 * @see      request\AbstractRequest::isDelete()
 *
 * @method   static string getServer($key = null, $default = null)
 * @see      request\AbstractRequest::getServer()
 * @method   static string getEnv($key = null, $default = null)
 * @see      request\AbstractRequest::getEnv()
 *
 * @method   static string getModule()
 * @see      request\AbstractRequest::getModule()
 * @method   static void   setModule($name)
 * @see      request\AbstractRequest::setModule()
 *
 * @method   static string getController()
 * @see      request\AbstractRequest::getController()
 * @method   static void   setController($name)
 * @see      request\AbstractRequest::setController()
 *
 * @method   static string|array getQuery($key = null, $default = null)
 * @see      Bluz\Http\Request::getQuery()
 * @method   static string|array getPost($key = null, $default = null)
 * @see      Bluz\Http\Request::getPost()
 * @method   static string|array getCookie($key = null, $default = null)
 * @see      Bluz\Http\Request::getCookie()
 *
 * @method   static string getHttpHost()
 * @see      Bluz\Http\Request::getHttpHost()
 * @method   static string getScheme()
 * @see      Bluz\Http\Request::getScheme()
 *
 * @method   static string getAccept()
 * @see      Bluz\Http\Request::getAccept()
 * @method   static string getHeader($header)
 * @see      Bluz\Http\Request::getHeader()
 *
 * @method   static FileUpload getFileUpload()
 * @see      Bluz\Http\Request::getFileUpload()
 * @method   static void setFileUpload(FileUpload $fileUpload)
 * @see      Bluz\Http\Request::setFileUpload()
 *
 * @method   static bool isXmlHttpRequest()
 * @see      Bluz\Http\Request::isXmlHttpRequest()
 * @method   static bool isFlashRequest()
 * @see      Bluz\Http\Request::isFlashRequest()
 *
 */
class Request extends AbstractProxy
{
    /**
     * @const string HTTP METHOD constant names
     */
    const METHOD_OPTIONS = Instance::METHOD_OPTIONS;
    const METHOD_GET = Instance::METHOD_GET;
    const METHOD_HEAD = Instance::METHOD_HEAD;
    const METHOD_PATCH = Instance::METHOD_PATCH;
    const METHOD_POST = Instance::METHOD_POST;
    const METHOD_PUT = Instance::METHOD_PUT;
    const METHOD_DELETE = Instance::METHOD_DELETE;
    const METHOD_TRACE = Instance::METHOD_TRACE;
    const METHOD_CONNECT = Instance::METHOD_CONNECT;

    /**
     * Command line request
     */
    const METHOD_CLI = Instance::METHOD_CLI;

    /**
     * HTTP Request
     */
    const METHOD_HTTP = Instance::METHOD_HTTP;

    /**
     * @const string HTTP ACCEPT MIME types
     */
    const ACCEPT_CLI = Instance::ACCEPT_CLI;
    const ACCEPT_HTML = Instance::ACCEPT_HTML;
    const ACCEPT_JSON = Instance::ACCEPT_JSON;
    const ACCEPT_JSONP = Instance::ACCEPT_JSONP;
    const ACCEPT_XML = Instance::ACCEPT_XML;
    
    /**
     * Init instance
     *
     * @throws ComponentException
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
     //   throw new ComponentException("Class `Proxy\\Request` required external initialization");
    }
}
