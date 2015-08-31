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

version_compare(phpversion(), '5.5.0', '>=') === true or die ('PHP 5.5.0 is required, you have ' . phpversion());

// защита страницы
defined('PROTECT_PAGE') or define('PROTECT_PAGE', 1);

// use for production mode 'production' or for developer 'developer'
defined('APP_MODE') or define('APP_MODE', (getenv('APP_MODE') === 'developer') ? 'developer' : 'production');

/** @noinspection TernaryOperatorSimplifyInspection */
defined('DEBUG_MODE') or define('DEBUG_MODE', (getenv('DEBUG_MODE') === 'true') ? true : false);

/** @noinspection TernaryOperatorSimplifyInspection */
defined('DEBUG_LOG') or define('DEBUG_LOG', (getenv('DEBUG_LOG') === 'true') ? true : false);

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
/**
 * путь к корневой папке сайта
 */
/** @noinspection RealpathOnRelativePathsInspection */
defined('SITE_PATH') or define('SITE_PATH', realpath(__DIR__ . DS . '..' . DS .'..' . DS . '..' . DS) . DS);

/**
 * путь к framework
 */
/** @noinspection RealpathOnRelativePathsInspection */
defined('ROOT_PATH') or define('ROOT_PATH', realpath(__DIR__. DS . '..' . DS. '..' . DS) . DS);
defined('APP_PATH') or define('APP_PATH', SITE_PATH . 'application' . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', SITE_PATH . 'vendor' . DS);
defined('DATA_PATH') or define('DATA_PATH', SITE_PATH . 'data');
defined('PUBLIC_PATH') or define('PUBLIC_PATH', SITE_PATH . 'public');

set_include_path(ini_get('include_path') . PATH_SEPARATOR . __DIR__);
ini_set('session.auto_start', 1);

// инициализация базы
// для гостевой
define('TBL_POSTS', 'gb_posts');  // имя таблицы с сообщениями
define('TBL_REPLIES', 'gb_replies'); // имя таблицы с ответами
define('TBL_USERS', 'gb_users'); // имя таблицы с модераторами


// Активируем проверку утверждений и сделаем его фоновым
assert_options(ASSERT_ACTIVE, DEBUG_MODE);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 0);
assert_options(ASSERT_BAIL, 1);
assert_options( ASSERT_CALLBACK, 'assert_callback');

/**
 * @param      $file
 * @param      $line
 * @param      $message
 * @param null $desc описание проверки
 */
function assert_callback($file, $line, $message, $desc = null) {

    echo "<h1>Тест провален!</h1>
        File: <strong>$file</strong> in
        Line: <strong>$line</strong><br/>
        Test: <b>$message</b><br/>";
    if ($desc) {
        echo "Описание: <b>$desc</b><br/>";
    }
}