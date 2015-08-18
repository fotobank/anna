<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace application;

use common;
use proxy\Response;
use proxy\Router;
use application\Exception\RedirectException;
use application\Exception\ReloadException;
use exception\ApplicationException;
use proxy\Server;
use view\View;


/**
 * Application

 * @method void denied()
 * @method void redirect(string $url)
 * @method void redirectTo(string $controller, string $method, array $params = [])
 * @method void reload()
 *
 */
class Application
{
    use common\Container\Helper;
    use common\Container\Singleton;

    /**
     * @var string Application path
     */
    protected $path;

    /**
     * @var array Stack of widgets closures
     */
    protected $widgets = [];

    /**
     * Get path to Application
     * @return string
     */
    public function getPath()
    {
        if (!$this->path) {
            if (defined('PATH_APPLICATION')) {
                $this->path = PATH_APPLICATION;
            } else {
                $reflection = new \ReflectionClass($this);
                // 3 level up
                $this->path = dirname(dirname(dirname($reflection->getFileName())));
            }
        }
        return $this->path;
    }

    /**
     * Get widget file
     * @param  string $module
     * @param  string $widget
     * @return string
     * @throws ApplicationException
     */
    protected function getWidgetFile($module, $widget)
    {
        $widgetPath = $this->getPath() . '/modules/' . $module . '/widgets/' . $widget . '.php';

        if (!file_exists($widgetPath)) {
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
         * @var \Closure $widgetClosure
         */
        if (isset($this->widgets[$module], $this->widgets[$module][$widget])) {
            $widgetClosure = $this->widgets[$module][$widget];
        } else {
            $widgetClosure = include $widgetFile;

            if (!isset($this->widgets[$module])) {
                $this->widgets[$module] = [];
            }
            $this->widgets[$module][$widget] = $widgetClosure;
        }

        if (!is_callable($widgetClosure)) {
            throw new ApplicationException("Widget is not callable '$module/$widget'");
        }

        return $widgetClosure;
    }


    /**
     * Initialize process
     *
     * @throws \exception\ApplicationException
     */
    public function init()
    {
        try {
            // initial default helper path
            $this->addHelperPath(__DIR__ . '/Helper/');

            // init router
            Router::start();
            $view = new View();
            $view->render();

        } catch (RedirectException $e) {
            Response::setException($e);
            Response::setStatusCode($e->getCode());
            Response::setHeader('Location', $e->getMessage());

            return null;

        } catch (ReloadException $e) {
            Response::setException($e);
            Response::setStatusCode($e->getCode());
            Response::setHeader('Refresh', '0; url=' . Server::get('REQUEST_URI'));

            return null;

        } catch (\Exception $e) {

            throw $e;
        }
    }

}
