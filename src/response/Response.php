<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace response;

use common\Options;

/**
 * Response
 *
 * @package  Response
 */
class Response
{
    use Options;

    /**
     * @var string HTTP protocol version
     */
    protected $protocol = '1.1';

    /**
     * @var int Response code equal to HTTP status codes
     */
    protected $code = 200;

    /**
     * @var string|null HTTP Phrase
     */
    protected $phrase;

    /**
     * @var array Stack of headers
     */
    protected $headers = [];

    /**
     * @var array Stack of cookies
     */
    protected $cookies = [];


    /**
     * @param $action
     * @param $url
     * @param $code
     */
    public function pushHeader($action = '', $url = '', $code = 200)
{
    $this->code = $code;
    $this->setHeader($action, $url);
    // setup response code
    http_response_code($this->code);

    // send stored cookies
    foreach ($this->cookies as $cookie) {
        setcookie(array_values($cookie));
    }
    // send stored headers
    if(empty($action or $url))
    {
        if(getenv('HTTP_REFERER'))
        {
            header('location: ' . getenv('HTTP_REFERER'));
        }
        else
        {
            header('location: /index.php');
        }
    }
    else
    {
        foreach ($this->headers as $key => $value)
        {
            header($key .': '. implode(', ', $value));
        }
    }
    exit();
}

    /**
     * Gets the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * Gets the response Status-Code.
     *
     * The Status-Code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Sets the status code of this response.
     * @param int $code The 3-digit integer result code to set.
     * @return void
     */
    public function setStatusCode($code)
    {
        $this->code = (int)$code;
    }

    /**
     * Gets the response Reason-Phrase, a short textual description of the Status-Code.
     *
     * Because a Reason-Phrase is not a required element in response
     * Status-Line, the Reason-Phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 2616 recommended reason phrase for the
     * response's Status-Code.
     *
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase()
    {
        return $this->phrase;
    }

    /**
     * Sets the Reason-Phrase of the response.
     *
     * If no Reason-Phrase is specified, implementations MAY choose to default
     * to the RFC 2616 recommended reason phrase for the response's Status-Code.
     *
     * @param string $phrase The Reason-Phrase to set.
     */
    public function setReasonPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * Retrieve a header by the given case-insensitive name as a string.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * @param string $header Case-insensitive header name.
     * @return string
     */
    public function getHeader($header)
    {
        if ($this->hasHeader($header)) {
            return implode(', ', $this->headers[$header]);
        } else {
            return '';
        }
    }

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderAsArray($header)
    {
        if ($this->hasHeader($header)) {
            return $this->headers[$header];
        } else {
            return [];
        }
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $header Case-insensitive header name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header)
    {
        return isset($this->headers[$header]);
    }

    /**
     * Sets a header, replacing any existing values of any headers with the
     * same case-insensitive name.
     *
     * The header name is case-insensitive. The header values MUST be a string
     * or an array of strings.
     *
     * @param string $header Header name
     * @param string|string[] $value  Header value(s)
     * @return void
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = (array)$value;
    }

    /**
     * Appends a header value for the specified header.
     *
     * Existing values for the specified header will be maintained. The new
     * value will be appended to the existing list.
     *
     * @param string $header Header name to add
     * @param string $value  Value of the header
     * @return void
     */
    public function addHeader($header, $value)
    {
        if ($this->hasHeader($header)) {
            $this->headers[$header][] = $value;
        } else {
            $this->setHeader($header, $value);
        }
    }

    /**
     * Remove a specific header by case-insensitive name.
     *
     * @param string $header HTTP header to remove
     * @return void
     */
    public function removeHeader($header)
    {
        unset($this->headers[$header]);
    }

    /**
     * Gets all message headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     * @return array Returns an associative array of the message's headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets headers, replacing any headers that have already been set on the message.
     *
     * The array keys MUST be a string. The array values must be either a
     * string or an array of strings.
     *
     * @param array $headers Headers to set.
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Merges in an associative array of headers.
     *
     * Each array key MUST be a string representing the case-insensitive name
     * of a header. Each value MUST be either a string or an array of strings.
     * For each value, the value is appended to any existing header of the same
     * name, or, if a header does not already exist by the given name, then the
     * header is added.
     *
     * @param array $headers Associative array of headers to add to the message
     * @return void
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge_recursive($this->headers, $headers);
    }

    /**
     * Remove all headers
     * @return void
     */
    public function removeHeaders()
    {
        $this->headers = [];
    }

    /**
     * Set Cookie
     *
     * @param string $name
     * @param string $value
     * @param int|string|\DateTime $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return void
     */
    public function setCookie(
                                $name,
                                $value = null,
                                $expire = 0,
                                $path = '/',
                                $domain = null,
                                $secure = false,
                                $httpOnly = true
                                )
    {
        // from PHP source code
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException('The cookie name contains invalid characters.');
        }

        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        // convert expiration time to a Unix timestamp
        if ($expire instanceof \DateTime) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);
            if (false === $expire || -1 === $expire) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }

        $this->cookies[$name] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => empty($path) ? '/' : $path,
            'domain' => $domain,
            'secure' => (bool)$secure,
            'httpOnly' => (bool)$httpOnly
        ];
    }

    /**
     * Get Cookie by name
     *
     * @param string $name
     * @return array|null
     */
    public function getCookie($name)
    {
        return isset($this->cookies[$name])?$this->cookies[$name]:null;
    }
}
