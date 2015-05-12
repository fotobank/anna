<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 01.05.2015
 * Time: 0:12
 */


namespace web\index;

use Db as Db;

if ( session_id() == '' )
	session_start();
header( 'Content-type: text/html; charset=windows-1251' );


	/**
	 * Class ajaxSite_web_index
	 */
	class IndexPage {

		public $categorii;
		public $admin_mode;
		public $razdel;
		public $title;
		public $keywords;
		public $description;
		// телефон в слайдере
		public $HTTP_HOST;
		// лайтбокс в шапке
		public $items;
		public $pags;
		// меню статей
		public $li_title;
		// колонка новостей
		public $filenews;
		//карусель
		public $carousel;
		// footer
		public $debug_mode;
		public $getTime;
		public $getMemoryPeak;
		public $getMemoryUsage;
		public $auto_copyright;
		public $PHP_SESSID;
		public $lite_box_path = "files/slides/*.jpg";
		public $section_title = [ "Главная"              => "/index",
								  "Об&nbsp;&nbsp;авторе" => "/about",
								  "Портфолио"            => "/portfolio",
								  "Новости"              => "/news",
								  "Услуги"               => "/services",
								  "Гостевая"             => "/comments"
		];
		private $current_razdel;

		/**
		 *
		 */
		public function __construct() {

			$this->categorii  = $this->categorii();
			$this->admin_mode = if_admin( true );
			list( $this->current_razdel, $this->title, $this->keywords, $this->description ) = title();
			$this->razdel = $this->_razdel();
			// телефон в слайдере
			$this->HTTP_HOST = $_SERVER['HTTP_HOST'];
			// лайтбокс в шапке
			$this->_lite_box();
			// меню статей
			$this->li_title = $this->_li_title();
			// колонка новостей
			$this->filenews = get_filenews( "news.txt" );
			//карусель
			$this->carousel = carousel();
			// footer
			$this->debug_mode     = DEBUG_MODE;
			$this->auto_copyright = auto_copyright( '2011' );
			$this->PHP_SESSID     = isset( $_COOKIE['PHPSESSID'] ) ? $_COOKIE['PHPSESSID'] : ip();

		}

		/**
		 * @return mixed
		 */
		protected function categorii() {

			self::db()->orderBy('position', 'ASC');

			return self::db()->get('index_menu', null, ['id', 'name_head']);

		}

		/**
		 * @return object
		 */
		protected static function db() {
			return Db::getInstance(Db::get_param());
		}

		/**
		 * @return array
		 */
		private function _razdel() {

			$razdel = [];
			if ($this->current_razdel) {
				foreach ($this->section_title as $key => $value) {
					$razdel[] = [
						"global_menu_name" => $key,
						"global_menu_href" => $value,
						"current" => ($this->current_razdel == $value.".php") ? "current" : ""
					];
				}
			}

			return $razdel;
		}

		private function _lite_box() {

			/*лайтбокс на главной в шапке*/
			$this->items = [];
			$this->pags  = [];
			$index_slide = glob($this->lite_box_path); // сканирование без субдиректорий
			if (is_array($index_slide)) {
				foreach ($index_slide as $key => $slide) {

					$this->items[]['img_src_head_index_slide'] = $slide;
					$this->pags[]['i']                         = $key + 1;

				}
			}
		}

		/**
		 * @return array
		 */
		private function _li_title() {

			$titles = [];
			if (is_array($this->categorii)) {
				foreach ($this->categorii as $row) {
					$titles[] = ['id_title' => $row['id'], 'name_title' => $row['name_head']];
				}
			}

			return $titles;
		}

		/**
		 * A helper method to sanitize a string:
		 *
		 * @param $text
		 *
		 * @return mixed|string
		 */
		public static function esc($text) {

			$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));

			return $text;
		}

		/**
		 * @param $txt_err
		 *
		 * @throws \Exception
		 */
		protected static function if_error($txt_err) {

			if (self::db()->getLastError() != '&nbsp;&nbsp;') {
				throw new \Exception($txt_err." ".self::db()->getLastError());
			}

		}


	}