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

version_compare(phpversion(), '5.5.0', '>=') === true or die ('PHP5.5 Only');

// Константы:
defined('PROTECT_PAGE') or define('PROTECT_PAGE', 1);
// use for production mode 'prod' or for developer 'dev'
defined('APP_MODE') or define('APP_MODE', 'dev');
defined('PATH_SEPARATOR') or define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('SALT') or define('SALT', 'qE3!nT^(gj)+?|6~d&.ru|');
// настройки для classa Recursive
defined('SCAN_DIR_NAME') or define('SCAN_DIR_NAME', 1);
defined('SCAN_BASE_NAME') or define('SCAN_BASE_NAME', 2);
defined('SCAN_MULTI_ARRAY') or define('SCAN_MULTI_ARRAY', 4);
// максимальный размер выводимого изображения
defined('MAX_IMG_SIZE') or define('MAX_IMG_SIZE', 5*1024*1024);

// путь к корневой папке сайта
/** @noinspection RealpathOnRelativePathsInspection */
defined('SITE_PATH') or define('SITE_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS) . DS);

set_include_path(ini_get('include_path') . PATH_SEPARATOR . __DIR__);
ini_set('session.auto_start', 1);

// инициализация базы
// для гостевой
define('TBL_POSTS', 'gb_posts');  // имя таблицы с сообщениями
define('TBL_REPLIES', 'gb_replies'); // имя таблицы с ответами
define('TBL_USERS', 'gb_users'); // имя таблицы с модераторами