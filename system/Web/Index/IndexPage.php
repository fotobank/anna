<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 01.05.2015
 * Time: 0:12
 */


namespace Web;


use Db as Db;
use Web\General\General;


if (session_id() == '') {
	session_start();
}
header('Content-type: text/html; charset=windows-1251');


/**
 * Class ajaxSite_web_index
 */
class IndexPage extends General
{

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
	// колонка новостей
	public $filenews = "news.txt";
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
	public $section_title = [
		"Главная"              => "/index",
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
	public function __construct()
		{

			$this->categorii = $this->getDbTitleName();
			$this->admin_mode = if_admin(true);
			list($this->current_razdel, $this->title, $this->keywords, $this->description) = title();
			$this->razdel = $this->prepareGlobalHeartMenu();
			// телефон в слайдере
			$this->HTTP_HOST = $_SERVER['HTTP_HOST'];
			// лайтбокс в шапке
			$this->liteBox();
			//карусель
			$this->carousel = carousel();
			// footer
			$this->debug_mode = DEBUG_MODE;
			$this->auto_copyright = auto_copyright('2011');
			$this->PHP_SESSID = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : ip();

		}


	/**
	 *
	 * @return string
	 */
	function getNews() {
		$print = "";
		$news = ( file_exists( $this->filenews ) ) ? file_get_contents( $this->filenews ) : $print;
		if ( $news != $print) {
			$news = explode( "||", $this->replaceBBCode( $news ) );
			if ( count( ( $news ) ) ) {
				for ( $i = 0; $i < count( $news ); $i ++ ) {
					$new = explode( "[]", $news[$i] );
					if ( count( $new ) > 0 ) {

						$print[$i]["titleNews"] = trim($new[0]);
						$print[$i]["bodyNews"] = isset( $new[1] ) ? $new[1] : false;
						if(isset($new[1])) {
							$print[$i]["body"] = true;
							$print[$i]["bodyNews"] = $new[1];
						}
						if(isset($new[2])) {
							$print[$i]["link"] = true;
							$print[$i]["linkNewsDetail"] = "/news.php";
						} else {
							$print[$i]["link"] = false;
						}
					}
				}
			}
		} else {
			$print["titleNews"] = "Файл новостей не найден";
		}
		return $print;
	}

	/**
	 * @param $text_post
	 *
	 * @return mixed
	 */
	private function replaceBBCode( $text_post ) {
		$str_search  = [
			"#\[del\](.+?)\[\/del\]#is",
			"#\[komm\](.+?)\[\/komm\]#is",
			"#\[y\](.+?)\[\/y\]#is",
			//        "#\\\n#is",
			"#\[b\](.+?)\[\/b\]#is",
			"#\[i\](.+?)\[\/i\]#is",
			"#\[u\](.+?)\[\/u\]#is",
			"#\[code\](.+?)\[\/code\]#is",
			"#\[quote\](.+?)\[\/quote\]#is",
			"#\[url=(.+?)\](.+?)\[\/url\]#is",
			"#\[url\](.+?)\[\/url\]#is",
			"#\[img\](.+?)\[\/img\]#is",
			"#\[size=(.+?)\](.+?)\[\/size\]#is",
			"#\[color=(.+?)\](.+?)\[\/color\]#is",
			"#\[list\](.+?)\[\/list\]#is",
			"#\[listn](.+?)\[\/listn\]#is",
			"#\[\*\](.+?)\[\/\*\]#"
		];
		$str_replace = [
			"",
			"<p class=\"komment\">\\1</p>",
			"<span class=\"date\">\\1</span>",
			//        "<br />",
			"<b>\\1</b>",
			"<i>\\1</i>",
			"<span style='text-decoration:underline'>\\1</span>",
			"<code class='code'>\\1</code>",
			"<table width = '95%'><tr><td>Цитата</td></tr><tr><td class='quote'>\\1</td></tr></table>",
			"<a href='\\1'>\\2</a>",
			"<a href='\\1'>\\1</a>",
			"<img src='\\1' alt = 'Изображение' />",
			"<span style='font-size:\\1%'>\\2</span>",
			"<span style='color:\\1'>\\2</span>",
			"<ul>\\1</ul>",
			"<ol>\\1</ol>",
			"<li>\\1</li>"
		];
		return preg_replace( $str_search, $str_replace, $text_post );
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
	protected static function db()
		{
			return Db::getInstance(Db::getParam());
		}

	/**
	 * меню в шапке
	 * @return array
	 */
	private function prepareGlobalHeartMenu()
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

	private function liteBox()
		{

			/*лайтбокс на главной в шапке*/
			$this->items = [];
			$this->pags = [];
			$index_slide = glob($this->lite_box_path); // сканирование без субдиректорий
			if (is_array($index_slide)) {
				foreach ($index_slide as $key => $slide) {

					$this->items[]['img_src_head_index_slide'] = $slide;
					$this->pags[]['i'] = $key + 1;

				}
			}
		}

	/**
	 * меню статей
	 *
	 * @return array
	 */
	public function getLiTitleName()
		{

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
	public static function esc($text)
		{

			$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));

			return $text;
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


}