<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 01.05.2015
 * Time: 0:12
 */


namespace Web\Index;

use Web\Base\Base;

if (session_id() == '') {
	session_start();
}
header('Content-type: text/html; charset=windows-1251');


/**
 * Class ajaxSite_web_index
 */
class IndexPage extends Base
{

	// телефон в слайдере
	public $http_host;
	// лайтбокс в шапке
	public $items = [];
	public $pags = [];
	// колонка новостей
	public $filenews;

	public $lite_box_path;

	//карусель
	public $carousel = true;
	public $carousel_link;
	public $carousel_block_1;
	public $carousel_block_2;


	/**
	 * @param $options
	 */
	public function __construct($options)
		{
			// настройка свойств класса
			$this->setOptions($options);
			// инициализация конструктора родительского класса
			parent::__construct();
			// лайтбокс в шапке
			$this->liteBox();
			$this->_carousel();
		}


	/**
	 * @param $thumb_link
	 *
	 * @return string
	 * @internal param $num
	 *
	 */
	protected function photo_link($thumb_link)
		{

			list($name_dir, $path_thumb) = $thumb_link;
			$name_dir = substr($name_dir, 3);
			$name_dir = WinUtf($name_dir, 'w');
			$patcUtf8 = WinUtf($path_thumb, 'w');

			$img_path = str_replace("/thumb", "", $patcUtf8);
			$img_path = str_replace("files/portfolio/", "", $img_path);
			$patcUtf8 = basename($patcUtf8, '.jpg');
			$href_img_path = "/inc/wm.php?img={$img_path}";
			$src_img_path = "/inc/th.php?img={$img_path}";

			return [
				'href_img_path' => $href_img_path,
				'src_img_path'  => $src_img_path,
				'name_dir'      => $name_dir,
				'patcUtf8'      => $patcUtf8
			];
		}

	/**
	 * @return string
	 */
	public function _carousel()
		{
			/** сканирование в субдеррикториях 'thumb' */
			$thumb = get_random_elements(recursive_dir("files/portfolio", ".jpg", ['thumb'], [], false), 26);

			$thumb = array_chunk($thumb, 2);

			foreach ($thumb as $key => $value) {

				$this->carousel_link[$key]['carousel_block'][] = $this->photo_link($value['0']);
				$this->carousel_link[$key]['carousel_block'][] = $this->photo_link($value['1']);

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
	 *лайтбокс на главной в шапке
	 */
	protected function liteBox()
		{
			// сканирование без субдиректорий
			$index_slide = glob($this->lite_box_path);
			if (is_array($index_slide)) {
				foreach ($index_slide as $key => $slide) {

					$this->items[]['img_src_head_index_slide'] = $slide;
					$this->pags[]['i'] = $key + 1;
				}
			}
		}


	/**
	 *
	 * @return string
	 */
	public function getNews()
		{
			$print = "";
			$news = (file_exists($this->filenews)) ? file_get_contents($this->filenews) : $print;
			if ($news != "") {
				$news = explode("||", $this->replaceBBCode($news));
				if (count(($news))) {
					for ($i = 0; $i < count($news); $i ++) {
						$new = explode("[]", $news[$i]);
						if (count($new) > 0) {

							$print[$i]["titleNews"] = trim($new[0]);
							$print[$i]["bodyNews"] = isset($new[1]) ? $new[1] : false;
							if (isset($new[1])) {
								$print[$i]["body"] = true;
								$print[$i]["bodyNews"] = $new[1];
							}
							if (isset($new[2])) {
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
	 * @param $news
	 *
	 * @return mixed
	 * @internal param $text_post
	 *
	 */
	protected function replaceBBCode($news)
		{
			$str_search = [
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
				'',
				'<p class="komment">\\1</p>',
				'<span class="date">\\1</span>',
				'<b>\\1</b>',
				'<i>\\1</i>',
				'<span style="text-decoration: underline;">\\1</span>',
				'<code class="code">\\1</code>',
				'<table width = "95%"><tr><td>Цитата</td></tr><tr><td class="quote">\\1</td></tr></table>',
				'<a href="\\1">\\2</a>',
				'<a href="\\1">\\1</a>',
				'<img src="\\1" alt = "Изображение" />',
				'<span style="font-size: \\1%;">\\2</span>',
				'<span style="color: \\1;">\\2</span>',
				'<ul>\\1</ul>',
				'<ol>\\1</ol>',
				'<li>\\1</li>'
			];

			return preg_replace($str_search, $str_replace, $news);
		}
}