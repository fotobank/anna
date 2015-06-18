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

define('_SECUR', 1);
header( 'Content-type: text/html; charset=windows-1251' );


if (version_compare(phpversion(), '5.4.0', '<') === true) {
	die ('PHP5.4 Only');
}
// Константы:
if (!defined('PATH_SEPARATOR')) {
	define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
}
// разделитель для путей к файлам
define ('DS', DIRECTORY_SEPARATOR);
// путь к корневой папке сайта
define ('SITE_PATH', realpath(__DIR__.DS.'..'.DS.'..'.DS).DS);

set_include_path(ini_get('include_path').PATH_SEPARATOR.__DIR__);
ini_set('session.auto_start', 1);

// инициализация базы
// эти значения рекомендуется не изменять - для гостевой
$GLOBALS['tbl_posts'] = 'gb_posts';        // имя таблицы с сообщениями
$GLOBALS['tbl_replies'] = 'gb_replies';    // имя таблицы с ответами
$GLOBALS['tbl_users'] = 'gb_users';        // имя таблицы с модераторами