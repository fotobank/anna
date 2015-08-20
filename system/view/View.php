<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace view;

use auth\AbstractRowEntity;
use common\Container\Container;
use common\Container\JsonSerialize;
use common\Container\MagicAccess;
use common\Container\Helper;
use common\Options;
use exception\ViewException;
use proxy\Router;

/**
 * View
 *
 * @package  View

 *
 * @method string ahref(string $text, mixed $href, array $attributes = [])
 * @method string api(string $module, string $method, $params = [])
 * @method string attributes(array $attributes = [])
 * @method string baseUrl(string $file = null)
 * @method string checkbox($name, $value = null, $checked = false, array $attributes = [])
 * @method string|bool controller(string $controller = null)
 * @method string|View dispatch($module, $controller, $params = [])
 * @method string exception(\Exception $exception)
 * @method string|null headScript(string $script = null)
 * @method string|null headStyle(string $style = null, $media = 'all')
 * @method string|bool module(string $module = null)
 * @method string partial($__template, $__params = [])
 * @method string partialLoop($template, $data = [], $params = [])
 * @method string radio($name, $value = null, $checked = false, array $attributes = [])
 * @method string redactor($selector, array $settings = [])
 * @method string script(string $script)
 * @method string select($name, array $options = [], $selected = null, array $attributes = [])
 * @method string style(string $style, $media = 'all')
 * @method string|null url(string $module, string $controller, array $params = [], bool $checkAccess = false)
 * @method AbstractRowEntity|null user()
 * @method void widget($module, $widget, $params = [])
 *
 */
class View implements ViewInterface, \JsonSerializable
{
    use Container;
    use JsonSerialize;
    use MagicAccess;
    use Options;
    use Helper;

    /**
     * Constants for define positions
     */
    const POS_PREPEND = 'prepend';
    const POS_REPLACE = 'replace';
    const POS_APPEND = 'append';

    /**
     * @var string base url
     */
    protected $baseUrl;

    /**
     * @var string Path to template
     */
    protected $path;

    /**
     * @var array Paths to partial
     */
    protected $partialPath = [];

    /**
     * @var string Template name
     */
    protected $template;

    /**
     * Create view instance, initial default helper path
     */
    public function __construct()
    {
        // initial default helper path
        $this->addHelperPath(__DIR__ . '/Helper/');
    }

    /**
     * List of packed properties
     * @return string[]
     */
    public function __sleep()
    {
        return ['baseUrl', 'container', 'helpersPath', 'path', 'partialPath', 'template'];
    }

    /**
     * View should be callable
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke()
    {
        try
        {
        return $this->render();
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Render like string
     *
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        try
        {
            return $this->render();
        }
        catch(\Exception $e)
        {
            throw $e;
        }

    }

    /**
     * {@inheritdoc}
     * @param string $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     * @param string $file
     * @return void
     */
    public function setTemplate($file)
    {
        $this->template = $file;
    }

    /**
     * Add partial path for use inside partial and partialLoop helpers
     * @param string $path
     * @return View
     */
    public function addPartialPath($path)
    {
        $this->partialPath[] = $path;
        return $this;
    }

    /**
     * Render
     *
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        ob_start();
        try {
            // достаем параметры из router
            $current_method = Router::getCurrentMethod();
            $id = Router::getId();
            $param = Router::getParam();
            $instance = Router::getInstanceController();

            // вызываем метод с параметрами
            $instance->$current_method($id, $param);

        } catch (ViewException $e) {
//             clean output
            ob_end_clean();
            throw $e;
        }
        return ob_get_clean();
    }
}
