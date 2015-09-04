<?php
/**
 *
 * @created   by PhpStorm
 * @package   mustacheConfig.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     26.08.2015
 * @time:     1:00
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use Mustache_Engine as Mustache;
use Mustache_Loader_FilesystemLoader as Loader;
use Mustache_Logger_StreamLogger as Logger;


defined('MUSTACHE_TEMPLATES') or define('MUSTACHE_TEMPLATES', 'modules/Views/layouts');
defined('MUSTACHE_PARTIALS') or define('MUSTACHE_PARTIALS', 'modules/Views/layouts/partials');
defined('MUSTACHE_CACHE') or define('MUSTACHE_CACHE', 'assests/cache/mustache');
defined('MUSTACHE_LOG') or define('MUSTACHE_LOG', 'assests/log');


return [
        // 'template_class_prefix' => '__MyTemplates_',
        'cache'                  => (ROOT_PATH . MUSTACHE_CACHE),
        'cache_file_mode'        => 0666,
        // Please, configure your umask instead of doing this :)
        'cache_lambda_templates' => true, 'loader' => new Loader(ROOT_PATH . MUSTACHE_TEMPLATES),
        'partials_loader'        => new Loader(ROOT_PATH . MUSTACHE_PARTIALS),
        // 'helpers' => [ 'i18n' => function($text) {  } ],
        'escape'                 => function ($value)
        {
            return htmlspecialchars($value, ENT_COMPAT, 'windows-1251');
        },
        'charset'                => 'windows-1251',
        'logger'                 => new Logger(ROOT_PATH . MUSTACHE_LOG),
        'strict_callables'       => true, 'pragmas' => [Mustache::PRAGMA_FILTERS],
];