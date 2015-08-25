<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace application;

use classes\Router\Router as MainRouter;
use common;
use DI\ContainerBuilder;
use exception\ApplicationException;
use application\Exception\ReloadException;
use application\Exception\RedirectException;
use proxy\Config;
use proxy\Response;
use proxy\Server;
use proxy\Router;


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
    use common\Helper;
    use common\Singleton;

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
            if(defined('PATH_APPLICATION'))
            {
                $this->path = PATH_APPLICATION;
            }
            else
            {
                $reflection = new \ReflectionClass($this);
                // 3 level up
                $this->path = dirname(dirname(dirname($reflection->getFileName())));
            }
        }

        return $this->path;
    }

    /**
     * Get widget file
     *
     * @param  string $module
     * @param  string $widget
     *
     * @return string
     * @throws ApplicationException
     */
    protected function getWidgetFile($module, $widget)
    {
        $widgetPath = $this->getPath() . '/modules/' . $module . '/widgets/' . $widget . '.php';

        if(!file_exists($widgetPath))
        {
            throw new ApplicationException("Widget file not found '$module/$widget'");
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
     * @param array  $params
     *
     * @return \Closure
     * @throws \exception\ApplicationException
     */
    public function widget($module, $widget, $params = [])
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
     * @param string $environment
     *
     * @return MainRouter
     * @throws \Exception
     */
    public function init($environment = 'production')
    {
        try
        {
//            $t = new Table();
//            $t->authenticateEquals('admin', 'admin');
//            $t->authenticateToken('f9705d72d58b2a305ab6f5913ba60a61');

            $this->environment = $environment;
            // initial default helper path
            $this->addHelperPath(__DIR__ . '/Helper/');


            $container = ContainerBuilder::buildDevContainer();

            $container->set('MainRouter', function () {
                return $this->initRouter();
            });

            $router = $container->get('MainRouter');



            //  $router = $this->initRouter();
           return $router;

        }
        catch(RedirectException $e)
        {
            // Redirect
            Response::removeHeaders();
            Response::pushHeader('Location', $e->getMessage(), $e->getCode());

            return null;

            // Reload
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
}
