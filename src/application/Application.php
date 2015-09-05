<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace application;

use core\Framework;
use router\Router as MainRouter;
use common\Helper;
use exception\ApplicationException;
use application\Exception\ReloadException;
use application\Exception\RedirectException;
use proxy\Response;
use proxy\Server;
use router\Router;


/**
 * Application
 * @method void denied()
 * @method void redirect(string $url)
 * @method void redirectTo($controller, string $method = null, array $params = [])
 * @method void reload()
 * @method MainRouter initRouter()
 *
 */
class Application
{
    use Helper;

    /**
     * @var string Application path
     */
    protected $path;

    /**
     * @var string Environment name
     */
    protected $environment = 'production';

    /**
     * @var array Stack of widgets closures
     */
    protected $widgets = [];

    /** @var  Router */
    protected  $router;

    /**
     * @param \router\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->environment = APP_MODE;
        // initial default helper path
        $this->addHelperPath(__DIR__ . '/Helper/');
    }

    /**
     * Get application environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get path to Application
     *
     * @return string
     */
    public function getPath()
    {
        if(!$this->path)
        {
            if(defined('APP_PATH'))
            {
                $this->path = APP_PATH;
            }
            else
            {
                $reflection = new \ReflectionClass($this);
                $this->path = dirname(dirname(dirname($reflection->getFileName()))). 'application' . DS;
            }
        }

        return $this->path;
    }

    /**
     * Get widget file
     *
     * @param         $controller
     * @param  string $widget
     *
     * @return string
     * @throws \exception\ApplicationException
     * @internal param string $module
     */
    protected function getWidgetFile($controller, $widget)
    {
        $widgetPath = $this->getPath() . '/modules/' . $controller . '/widgets/' . $widget . '.php';

        if(!file_exists($widgetPath))
        {
            throw new ApplicationException("Widget file not found '$controller/$widget'");
        }

        return $widgetPath;
    }

    /**
     * Widget call
     *
     * Call widget from any Package
     *     Application::getInstance()->widget($module, $widget, array $params);
     *
     * @api
     *
     * @param string $module
     * @param string $widget
     *
     * @return \Closure
     * @throws \exception\ApplicationException
     * @internal param array $params
     *
     */
    public function widget($module, $widget)
    {
        $widgetFile = $this->getWidgetFile($module, $widget);

        /**
         * Cachable widgets
         *
         * @var \Closure $widgetClosure
         */
        if(isset($this->widgets[$module], $this->widgets[$module][$widget]))
        {
            $widgetClosure = $this->widgets[$module][$widget];
        }
        else
        {
            /** @noinspection PhpIncludeInspection */
            $widgetClosure = include $widgetFile;

            if(!isset($this->widgets[$module]))
            {
                $this->widgets[$module] = [];
            }
            $this->widgets[$module][$widget] = $widgetClosure;
        }

        if(!is_callable($widgetClosure))
        {
            throw new ApplicationException("Widget is not callable '$module/$widget'");
        }

        return $widgetClosure;
    }


    /**
     * Initialize process
     *
     * @return \router\Router
     * @throws \Exception
     */
    public function run()
    {
        try
        {
//            $t = new Table();
//            $t->authenticateEquals('admin', 'admin');
//            $t->authenticateToken('f9705d72d58b2a305ab6f5913ba60a61');

            $this->router->runInstance();

        }
        catch(RedirectException $e)
        {
            Response::removeHeaders();
            Response::pushHeader('Location', $e->getMessage(), $e->getCode());

            return null;
        }
        catch
        (ReloadException $e)
        {
            Response::removeHeaders();
            Response::pushHeader('Refresh', '0; url=' . Server::get('REQUEST_URI'), $e->getCode());

            return null;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * @return \router\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \router\Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }
}