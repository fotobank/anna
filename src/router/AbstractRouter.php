<?php

namespace router;

use InvalidArgumentException;
use common\Helper;
use proxy\Config;
use proxy\Db;


/**
 * Class AbstractRouter
 *
 * @package router\AbstractRouter
 */
class AbstractRouter implements InterfaceRouter
{

    use Helper;

    const GET     = 1;
    const POST    = 2;
    const PUT     = 4;
    const DELETE  = 8;
    const HEAD    = 16;
    const TRACE   = 32;
    const OPTIONS = 64;
    const CONNECT = 128;

    protected $routes = [];
    protected $current_route = [];
    protected $routeFactory;
    protected $defaultCallback;

    /**
     * @param RouteFactory $routeFactory Optionally supply an instance of a RouteFactory
     *  that may instantiate alternative Route objects. Defaults to standard RouteFactory.
     */
    public function __construct(RouteFactory $routeFactory = null) {
        if (!$routeFactory) {
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'RouteFactory.php';
            $routeFactory = new RouteFactory;
        }
        $this->routeFactory = $routeFactory;
        // add and avtorun plugins
        $this->addPluginsPath(__DIR__ . '/Plugins/');
    }

    /**
     * Create a new route using a pattern, dispatch array and callback and add it to the stack.
     * Matches GET, POST, PUT and DELETE request methods.
     *
     * @param string   $pattern
     * @param array    $dispatch
     * @param callable $callback
     *
     * @throws \Exception
     */
    public function add($pattern, array $dispatch = [], $callback = null) {
        try
        {
            $this->addMethodRoute(self::GET | self::POST | self::PUT | self::DELETE,
                                  $this->routeFactory->newRoute($pattern, $dispatch), $callback);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
    
    /**
     * Create a new route matching GET requests.
     *
     * @param string $pattern
     * @param array $dispatch
     * @param callable $callback
     */
    public function addGet($pattern, array $dispatch = [], $callback = null) {
        $this->addMethod(self::GET, $pattern, $dispatch, $callback);
    }

    /**
     * Create a new route matching POST requests.
     *
     * @param string $pattern
     * @param array $dispatch
     * @param callable $callback
     */
    public function addPost($pattern, array $dispatch = [], $callback = null) {
        $this->addMethod(self::POST, $pattern, $dispatch, $callback);
    }

    /**
     * Create a new route matching PUT requests.
     *
     * @param string $pattern
     * @param array $dispatch
     * @param callable $callback
     */
    public function addPut($pattern, array $dispatch = [], $callback = null) {
        $this->addMethod(self::PUT, $pattern, $dispatch, $callback);
    }

    /**
     * Create a new route matching DELETE requests.
     *
     * @param string $pattern
     * @param array $dispatch
     * @param callable $callback
     */
    public function addDelete($pattern, array $dispatch = [], $callback = null) {
        $this->addMethod(self::DELETE, $pattern, $dispatch, $callback);
    }

    /**
     * Create a new route matching a custom combination of request methods.
     *
     * @param int $method A bitmask of request methods.
     * @param string $pattern
     * @param array $dispatch
     * @param callable $callback
     */
    public function addMethod($method, $pattern, array $dispatch = [], $callback = null) {
        $this->addMethodRoute($method, $this->routeFactory->newRoute($pattern, $dispatch), $callback);
    }

    /**
     * Add a Route object and a callback to the stack.
     * Matches GET, POST, PUT and DELETE request methods.
     * 
     * @param Route $route
     * @param callable $callback
     * @throws InvalidArgumentException if $callback is not callable
     */
    public function addRoute(Route $route, $callback = null) {
        $this->addMethodRoute(self::GET | self::POST | self::PUT | self::DELETE, $route, $callback);
    }
    
    /**
     * Add a Route object with specified request method and a callback to the stack.
     * 
     * @param int $method A bitmask of request methods.
     * @param Route $route
     * @param callable $callback
     * @throws InvalidArgumentException if $callback is not callable
     */
    public function addMethodRoute($method, Route $route, $callback = null) {
        if ($callback && !is_callable($callback, true)) {
            throw new InvalidArgumentException('$callback must be of type callable, got ' . gettype($callback));
        }
        $this->routes[] = compact('method', 'route', 'callback');
    }

    /**
     * Set a default callback for routes that have no specific callback defined.
     *
     * @param callable $callback
     * @throws InvalidArgumentException if $callback is not callable
     */
    public function defaultCallback($callback) {
        if ($callback && !is_callable($callback, true)) {
            throw new InvalidArgumentException('$callback must be of type callable, got ' . gettype($callback));
        }
        $this->defaultCallback = $callback;
    }

    /**
     * Start the routing process.
     * Matches GET, POST, PUT and DELETE requests equally.
     *
     * @param string $url The URL to route.
     * @param callable $noMatch Callback in case no route matched.
     * @return mixed Return value of whatever callback was invoked.
     * @throws InvalidArgumentException if $noMatch is not callable.
     * @throws NotFoundException in case no route matched and no callback was supplied.
     */
    public function route($url, $noMatch = null) {
        return $this->routeMethod(self::GET | self::POST | self::PUT | self::DELETE, $url, $noMatch);
    }
    
    /**
     * Start the routing process for a specific request method.
     * The request method is supplied as string, i.e. can be plucked directly from $_SERVER['REQUEST_METHOD'].
     *
     * @param string $method The request method.
     * @param string $url The URL to route.
     * @param callable $noMatch Callback in case no route matched.
     * @return mixed Return value of whatever callback was invoked.
     * @throws InvalidArgumentException if $method is not supported or $noMatch is not callable.
     * @throws NotFoundException in case no route matched and no callback was supplied.
     */
    public function routeMethodFromString($method, $url, $noMatch = null) {
        return $this->routeMethod($this->stringToRequestMethod($method), $url, $noMatch);
    }
    
    /**
     * Start the routing process for a specific request method.
     *
     * @param int $method Bitmask of the request method.
     * @param string $url The URL to route.
     * @param callable $noMatch Callback in case no route matched.
     * @return mixed Return value of whatever callback was invoked.
     * @throws InvalidArgumentException if $noMatch is not callable.
     * @throws NotFoundException in case no route matched and no callback was supplied.
     */
    public function routeMethod($method, $url, $noMatch = null) {
        if ($noMatch && !is_callable($noMatch, true)) {
            throw new InvalidArgumentException('$noMatch must be of type callable, got ' . gettype($noMatch));
        }

        foreach ($this->routes as $route) {
            if (!($route['method'] & $method)) {
                continue;
            }

            /** @var Route $match */
            $match = $route['route']->matchUrl($url);
            if ($match) {
                $this->current_route = $match->dispatchValues();
                $this->runPlugins();
                $this->checkLock($match);
                return $this->callback($route['callback'], $match);
            }
        }

        if ($noMatch) {
            return call_user_func($noMatch, $url);
        }
        
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'NotFoundException.php';
        throw new NotFoundException("No route matched $url");
    }

    /**
     * �������� ���������� ��������
     *
     * @param Route $match
     *
     * @throws \Exception
     */
    protected function checkLock(& $match)
    {
        try
        {
            $section_title = Config::getData('section_title');
            $controller    = strtolower($this->current_route['controller']);
            if(in_array('/' . $controller, $section_title, true))
            {
                Db::where('url', $controller);
                $lock = Db::getOne('lock_page');
                if($lock)
                {
                    $difference_time = $lock['end_date'] - time();
                    /** ���� ����� ������� �� ����� ��� ���� ����� �� �� ������� ��������� - ���������� �������� �������� */
                    if($difference_time > 0 || ($difference_time < 0 && $lock['auto_run'] !== 1))
                    {
                        $match->setDispatchValue('controller', $lock['controller']);
                        $match->setDispatchValue('action', $lock['action']);
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get a URL from a dispatch array.
     * Matches GET, POST, PUT and DELETE routes equally.
     *
     * @param array $dispatch
     * @return mixed Matching URL or false if no match.
     */
    public function reverseRoute(array $dispatch) {
        return $this->reverseRouteMethod(self::GET | self::POST | self::PUT | self::DELETE, $dispatch);
    }

    /**
     * Get a URL from a dispatch array for a specific method.
     *
     * @param       $method
     * @param array $dispatch
     *
     * @return mixed Matching URL or false if no match.
     */
    public function reverseRouteMethod($method, array $dispatch) {
        foreach ($this->routes as $route) {
            if (!($route['method'] & $method)) {
                continue;
            }
            /** @var Route $match */
            /** @var Route $route['route'] */
            $match = $route['route']->matchDispatch($dispatch);
            if ($match) {
                return $match->url();
            }
        }
        return false;
    }

    /**
     * Executes a callback or the default callback for a matched route.
     *
     * @param               $callback
     * @param \router\Route $route
     *
     * @return mixed
     */
    protected function callback($callback, Route $route) {
        try
        {
            if($callback)
            {
                if(!is_callable($callback, true))
                {
                    throw new InvalidArgumentException('$callback must be of type callable, got '. gettype($callback));
                }
                return call_user_func($callback, $route);
            }
            if($this->defaultCallback)
            {
                return call_user_func($this->defaultCallback, $route);
            }
            throw new NotFoundException(sprintf('Route %s matched URL %s, but no callback given',
                                                $route->pattern(), $route->url()));
        }
        catch(InvalidArgumentException $e)
        {
            throw $e;
        }catch(NotFoundException $e)
        {
            throw $e;
        }
    }

    /**
     * Returns matching request method constant from string representation.
     *
     * @param $string
     *
     * @return mixed
     */
    protected function stringToRequestMethod($string) {
        try
        {
            $self   = new \ReflectionClass($this);
            $method = $self->getConstant(strtoupper($string));
            if(!$method)
            {
                throw new InvalidArgumentException("Unsupported request method '$string'");
            }

            return $method;
        }
        catch(InvalidArgumentException $e)
        {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function getCurrentRoute()
    {
        return $this->current_route;
    }

}
