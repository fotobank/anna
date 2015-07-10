<?php
/**
 * �����
 * @created   by PhpStorm
 * @package   Controller.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     29.05.2015
 * @time      :     14:55
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Controller;

use Mustache_Engine as Mustache;
use Mustache_Autoloader;
use Mustache_Loader_FilesystemLoader as Loader;
use Mustache_Logger_StreamLogger as Logger;

/**
 * Class controller_Base
 * @package controllers
 */
abstract class Controller
{

	/**
	 * @var \Mustache_Engine
	 */
	public $mustache;

	/**
	 *
	 */
	public function init()
		{

			/**==========================��� ������� "������"====================*/
			if (isset($_POST['nick'], $_POST['email'])) {
				setcookie('nick', $_POST['nick'], time() + 300);
				setcookie('email', $_POST['email'], time() + 300);
			}
			/**==================================================================*/

			// mustache
			/** @noinspection PhpIncludeInspection */
			include(SITE_PATH . 'vendor/autoload.php');
			Mustache_Autoloader::register();
			// ������������� ������������� Mustache
			$this->mustache = new Mustache([
					   // 'template_class_prefix' => '__MyTemplates_',
					   'cache'                  => (SITE_PATH . 'cache/mustache'),
					   'cache_file_mode'        => 0666,
					   // Please, configure your umask instead of doing this :)
					   'cache_lambda_templates' => true,
					   'loader'                 => new Loader(SITE_PATH .'system/views/Mustache/templates'),
					  'partials_loader'         => new Loader(SITE_PATH .'system/views/Mustache/templates/partials'),
					  // 'helpers' => [ 'i18n'  => function($text) {  } ],
					   'escape'                 => function ($value) {
						   return htmlspecialchars($value, ENT_COMPAT, 'windows-1251');
					   },
					   'charset'                => 'windows-1251',
					   'logger'                 => new Logger(SITE_PATH . 'log'),
					   'strict_callables'       => true,
					   'pragmas'                => [Mustache::PRAGMA_FILTERS]
												   ]);

		}
}