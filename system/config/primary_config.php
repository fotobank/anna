<?php
/**
 *
 * @created   by PhpStorm
 * @package   config.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     0:25
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

//version_compare(phpversion(), '5.5.0', '>=') === true or die ('PHP5.5 Only');

if (version_compare(phpversion(), '5.5.0', '<')) {
    printf("PHP 5.5.0 is required, you have %s\n", phpversion());
    exit(1);
}

// защита страницы
defined('PROTECT_PAGE') or define('PROTECT_PAGE', 1);
// use for production mode 'production' or for developer 'developer'
defined('APP_MODE') or define('APP_MODE', (getenv('APP_MODE') === 'developer') ? 'developer' : 'production');
if (getenv('DEBUG_MODE') === 'true') {
    defined('DEBUG_MODE') or define('DEBUG_MODE', true);
} else {
    defined('DEBUG_MODE') or define('DEBUG_MODE', false);
}
defined('PATH_SEPARATOR') or define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('SALT') or define('SALT', 'qE3!nT^(gj)+?|6~d&.ru|');
// настройки для classa Recursive
defined('SCAN_DIR_NAME') or define('SCAN_DIR_NAME', 1);
defined('SCAN_BASE_NAME') or define('SCAN_BASE_NAME', 2);
defined('SCAN_MULTI_ARRAY') or define('SCAN_MULTI_ARRAY', 3);
defined('SCAN_CAROUSEL_ARRAY') or define('SCAN_CAROUSEL_ARRAY', 4);
// максимальный размер выводимого изображения
defined('MAX_IMG_SIZE') or define('MAX_IMG_SIZE', 5*1024*1024);
// Paths
// путь к корневой папке сайта
/** @noinspection RealpathOnRelativePathsInspection */
defined('SITE_PATH') or define('SITE_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS) . DS);
/** @noinspection RealpathOnRelativePathsInspection */
defined('PATH_ROOT') or define('PATH_ROOT', realpath(__DIR__. DS . '..' . DS) . DS);
defined('PATH_APPLICATION') or define('PATH_APPLICATION', PATH_ROOT . 'application' . DS);
defined('PATH_VENDOR') or define('PATH_VENDOR', SITE_PATH . 'vendor' . DS);
defined('PATH_DATA') or define('PATH_DATA', SITE_PATH . 'data');
defined('PATH_PUBLIC') or define('PATH_PUBLIC', SITE_PATH . 'public');

set_include_path(ini_get('include_path') . PATH_SEPARATOR . __DIR__);
ini_set('session.auto_start', 1);

// инициализация базы
// для гостевой
define('TBL_POSTS', 'gb_posts');  // имя таблицы с сообщениями
define('TBL_REPLIES', 'gb_replies'); // имя таблицы с ответами
define('TBL_USERS', 'gb_users'); // имя таблицы с модераторами