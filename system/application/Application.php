<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace application;

use common;
use exception\ApplicationException;
use proxy\Router;


/**
 * Application

 * @method void denied()
 * @method void redirect(string $url)
 * @method void redirectTo(string $module, string $controller, array $params = [])
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

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
