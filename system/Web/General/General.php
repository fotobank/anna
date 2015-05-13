<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 22:34
 */


namespace Web\General;


use Db as Db;


/**
 * Class General
 * @package Web\General
 */
class General {


	public $categorii;
	public $current_razdel;
	public $title;
	public $keywords;
	public $description;
	public $admin_mode;
// footer
	public $debug_mode;
	public $auto_copyright;
	public $PHP_SESSID;

	public $section_title = [
		"Главная"              => "/index",
		"Об&nbsp;&nbsp;авторе" => "/about",
		"Портфолио"            => "/portfolio",
		"Новости"              => "/news",
		"Услуги"               => "/services",
		"Гостевая"             => "/comments"
	];


	/**
	 *
	 */
	protected function init()
		{
			$this->categorii = $this->getDbTitleName();
			$this->admin_mode = if_admin(true);
			list($this->current_razdel, $this->title, $this->keywords, $this->description) = title();

			// footer
			$this->debug_mode = DEBUG_MODE;
			$this->auto_copyright = auto_copyright('2011');
			$this->PHP_SESSID = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : ip();
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
						"global_menu_name" => $key,
						"global_menu_href" => $value,
						"current"          => ($this->current_razdel == $value.".php") ? "current" : ""
					];
				}
			}

			return $razdel;
		}

	/**
	 * @return mixed
	 */
	protected function getDbTitleName()
		{
			self::db()->orderBy('position', 'ASC');
			return self::db()->get('index_menu', null, ['id', 'name_head']);
		}

	/**
	 * @return object
	 */
	protected function db()
		{
			return Db::getInstance(Db::getParam());
		}

	/**
	 * @param $txt_err
	 *
	 * @throws \Exception
	 */
	protected static function ifError($txt_err)
		{

			if (self::db()->getLastError() != '&nbsp;&nbsp;') {
				throw new \Exception($txt_err." ".self::db()->getLastError());
			}

		}

	/**
	 * A helper method to sanitize a string:
	 *
	 * @param $text
	 *
	 * @return mixed|string
	 */
	protected static function esc($text)
		{

			$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));

			return $text;
		}
}