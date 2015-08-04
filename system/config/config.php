<?php
/**
 *
 * @created   by PhpStorm
 * @package   config.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     0:25
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

defined('PROTECT_PAGE') or define('PROTECT_PAGE', 1);

if (version_compare(phpversion(), '5.5.0', '<') === true) {
	die ('PHP5.5 Only');
}
//  онстанты:
if (!defined('PATH_SEPARATOR')) {
	define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
}
// разделитель дл€ путей к файлам
define ('DS', DIRECTORY_SEPARATOR);
// путь к корневой папке сайта
define ('SITE_PATH', realpath(__DIR__.DS.'..'.DS.'..'.DS).DS);

set_include_path(ini_get('include_path').PATH_SEPARATOR.__DIR__);
ini_set('session.auto_start', 1);

// инициализаци€ базы
// дл€ гостевой
define('TBL_POSTS', 'gb_posts');  // им€ таблицы с сообщени€ми
define('TBL_REPLIES', 'gb_replies'); // им€ таблицы с ответами
define('TBL_USERS', 'gb_users'); // им€ таблицы с модераторами