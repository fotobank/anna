<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Litebox.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     18.06.2015
 * @time      :     3:07
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\Carousel;

use Common\Container\Options;


/**
 * Class Litebox
 * @package models\Litebox
 */
class Carousel
{

	protected $path;
	protected $real_path;

	use Options;

	/**
	 * @param $options
	 */
	public function __construct($options)
		{
			// настройка свойств класса
			$this->setOptions($options);
		}


	/**
	 * @param bool $thumb
	 */
	public function view($thumb = false)
		{

			$image = false;

			if ($this->path && preg_match('/\.(gif|jpeg|jpg|png)/i', $this->path)) {

				if (CODE_PAGE === 'windows-1251') {

					$this->path = mb_convert_encoding($this->path, 'Windows-1251', 'UTF-8');

				}

				$dirname = $basename = '';
				extract(path_info($this->path, EXTR_OVERWRITE));
				$this->real_path .= ($thumb) ? $dirname . '/thumb/' . $basename : $dirname . DS . $basename;
				$image = @imagecreatefromstring(@file_get_contents($this->real_path));
			}

			if (!$image) {

				error_log("\$realpath = " . $this->real_path . " \$image = " . $image, 0);
				$image = imagecreatefromstring(file_get_contents('../images/not_foto.png'));

			} elseif (!$thumb) {

				$w = imagesx($image);
				$h = imagesy($image);

				$watermark = imagecreatefrompng('../images/watermark.png');
				$ww = imagesx($watermark);
				$wh = imagesy($watermark);

				// вставить watermark в правый нижний угол
				imagecopy($image, $watermark, $w - $ww, $h - $wh, 0, 0, $ww, $wh);

				// или по центру
				// imagecopy($image, $watermark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);
			}
			// Send the image
			header('Content-type: image/jpeg');
			imagejpeg($image, null, 95);

		}
}