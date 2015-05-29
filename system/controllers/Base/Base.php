<?php
/**
 * Класс
 * @created   by PhpStorm
 * @package   Base.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     29.05.2015
 * @time      :     14:55
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Base;


/**
 * Class controller_Base
 * @package controllers
 */
class Base
{

	public $mustache;

	/**
	 *
	 */
	function init()
		{

			// mustache
			/** @noinspection PhpIncludeInspection */
			include(SITE_PATH . 'vendor/autoload.php');
			\Mustache_Autoloader::register();
			// инициализация шаблонизатора Mustache
			$this->mustache = new \Mustache_Engine([
					   // 'template_class_prefix' => '__MyTemplates_',
					   'cache'                  => (SITE_PATH . 'cache/mustache'),
					   'cache_file_mode'        => 0666,
					   // Please, configure your umask instead of doing this :)
					   'cache_lambda_templates' => true,
					   'loader'                 => new \Mustache_Loader_FilesystemLoader(SITE_PATH .
																						 'system/Views/Mustache/templates'),
					  'partials_loader'         => new \Mustache_Loader_FilesystemLoader(SITE_PATH .
																						 'system/Views/Mustache/templates/partials'),
					  // 'helpers' => [ 'i18n'  => function($text) {  } ],
					   'escape'                 => function ($value) {
						   return htmlspecialchars($value, ENT_COMPAT, 'windows-1251');
					   },
					   'charset'                => 'windows-1251',
					   'logger'                 => new \Mustache_Logger_StreamLogger(SITE_PATH .
																					 'log'),
					   'strict_callables'       => true,
					   'pragmas'                => [\Mustache_Engine::PRAGMA_FILTERS]
												   ]);

		}

}