<?php
/**
 * Класс View
 *
 * @created   by PhpStorm
 * @package   View.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     18.08.2015
 * @time:     22:58
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use view\View as Instance;
use auth\AbstractRowEntity;
use classes\Router\Router as MainRouter;


/**
 * Class View
 *
 * @package proxy
 *
 * @method static string ahref(string $text, mixed $href, array $attributes = [])
 * @method static string api(string $module, string $method, $params = [])
 * @method static string attributes(array $attributes = [])
 * @method static string baseUrl(string $file = null)
 * @method static string checkbox($name, $value = null, $checked = false, array $attributes = [])
 * @method static string|bool controller(string $controller = null)
 * @method static string|View dispatch($module, $controller, $params = [])
 * @method static string exception(\Exception $exception)
 * @method static string|null headScript(string $script = null)
 * @method static string|null headStyle(string $style = null, $media = 'all')
 * @method static string|bool module(string $module = null)
 * @method static string partial($__template, $__params = [])
 * @method static string partialLoop($template, $data = [], $params = [])
 * @method static string radio($name, $value = null, $checked = false, array $attributes = [])
 * @method static string redactor($selector, array $settings = [])
 * @method static string script(string $script)
 * @method static string select($name, array $options = [], $selected = null, array $attributes = [])
 * @method static string style(string $style, $media = 'all')
 * @method static string|null url(string $module, string $controller, array $params = [], bool $checkAccess = false)
 * @method static AbstractRowEntity|null user()
 * @method static void widget($module, $widget, $params = [])
 * @method static void setPath($path)
 * @method static void setTemplate($file)
 * @method static View addPartialPath($path)
 * @method static string render(MainRouter $router)
 */
class View extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
    }
}