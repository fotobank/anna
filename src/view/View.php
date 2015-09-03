<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace view;

use modules;
use auth\AbstractRowEntity;
use common\Container\Container;
use common\Container\JsonSerialize;
use common\Container\MagicAccess;
use common\Helper;
use common\Options;
use exception\ViewException;
use proxy\Post;
use Mustache_Engine as Mustache;


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
 * @method Mustache mustacheRegister();
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
     * @var \Mustache_Engine
     */
    public $mustache;

    /**
     * Create view instance, initial default helper path
     */
    public function __construct()
    {
        $this->addHelperPath(__DIR__ . '/Helper/');
        $this->mustache = $this->mustacheRegister();
    }

    /**
     * List of packed properties
     * @return string[]
     */
    public function __sleep()
    {
        return ['baseUrl'];
    }

    /**
     * View should be callable
     *
     * @param $template
     * @param $model
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke($template, $model)
    {
        try
        {
        return $this->render($template, $model);
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
//        return $this->render();
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
     * @param $template
     * @param $model
     *
     * @return string
     * @throws \Exception
     * @throws \exception\ViewException
     */
    public function render($template, $model)
    {
        ob_start();
        try {
            header('Content-type: text/html; charset=windows-1251');
            if(DEBUG_MODE)
            {
                setcookie('XDEBUG_SESSION', 'PHPSTORM', time() + 300);
            }
            /**==========================для раздела "отзывы"====================*/
            if(Post::_has('nick') && Post::has('email'))
            {
                setcookie('nick', Post::get('nick'), time() + 300);
                setcookie('email', Post::get('email'), time() + 300);
            }
            /**==================================================================*/


        echo $this->mustache->render($template, $model);

        } catch (ViewException $e) {

            ob_end_clean();
            throw $e;
        }
//        return ob_get_clean();
       ob_end_flush();
    }
}
