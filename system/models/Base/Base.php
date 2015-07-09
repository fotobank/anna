<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 22:34
 */


namespace models\Base;


use Common\Container\Options;
use Db as Db;


/**
 * Class General
 * @package models\Base
 */
abstract class Base
{

	use Options;

	public $categorii;
	public $current_razdel;
	public $title;
	public $keywords;
	public $description;
	public $admin_mode = false;
	public $file_meta_title;
	// показывать только заглавную страницу
	public $onluIndex = false;
	// footer
	public $debug_mode = false;
	public $auto_copyright;
	public $php_sessid;

	public $section_title = [
		'Главная'              => '/index',
		'Об&nbsp;&nbsp;авторе' => '/about',
		'Портфолио'            => '/portfolio',
		'Новости'              => '/news',
		'Услуги'               => '/services',
		'Гостевая'             => '/comments'
	];


	/**
	 *
	 */
	public function __construct()
		{
			$this->file_meta_title = SITE_PATH.'system/config/meta_title.ini';
			$this->admin_mode      = if_admin(true);
			// footer
			$this->debug_mode      = DEBUG_MODE;
			$this->auto_copyright  = auto_copyright('2011');
			$this->php_sessid      = array_key_exists('PHPSESSID', $_COOKIE) ? $_COOKIE['PHPSESSID'] : ip();

			$this->categorii = $this->getDbTitleName();
			$this->getMetaTitle();
		}

	/**
	 * присвоение значений переменным metatitle в шапке
	 */
	public function getMetaTitle()
		{
			if (is_file($this->file_meta_title)) {
				$arrayMetaTitle = parse_ini_file($this->file_meta_title, true);
				$this->current_razdel = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '/index.php';
				foreach ($arrayMetaTitle as $title => $metaData) {

					if ('/'.$title.'.php' === $this->current_razdel) {
						$this->title = $metaData['title'];
						$this->keywords = $metaData['keywords'];
						$this->description = $metaData['description'];
					}
				}
				if ($this->onluIndex) {
					$this->current_razdel = false;
				}

				return true;
			}
			throw new \Exception('не найден ini файл => '.$this->file_meta_title);
		}

	/**
	 * меню в шапке
	 * @return array
	 */
	public function globalHeartMenu()
		{
			$razdel = [];
			if ($this->current_razdel) {
				foreach ($this->section_title as $key => $value) {
					$razdel[] = [
						'global_menu_name' => $key,
						'global_menu_href' => $value,
						'current'          => ($this->current_razdel === $value.'.php') ? 'current' : ''
					];
				}
			}

			return $razdel;
		}

	/**
	 * @return mixed
	 */
	public function getDbTitleName()
		{
			self::db()->orderBy('position', 'ASC');

			return self::db()->get('index_menu', null, ['id', 'name_head']);
		}

	/**
	 * @return object
	 */
	public function db()
		{
			return Db::getInstance(Db::getParam());
		}

	/**
	 * @param $txt_err
	 *
	 * @throws \Exception
	 */
	public function ifError($txt_err)
		{

			if (self::db()->getLastError() !== '&nbsp;&nbsp;') {
				throw new \Exception($txt_err.' '.self::db()->getLastError());
			}

		}

	/**
	 * A helper method to sanitize a string:
	 *
	 * @param $text
	 *
	 * @return mixed|string
	 */
	public function esc($text)
		{

			$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));

			return $text;
		}

	/**
	 * показывать только заглавную страницу
	 *
	 * @param boolean $onluIndex
	 */
	public function setOnluIndex($onluIndex)
		{
			$this->onluIndex = $onluIndex;
		}

}