<?php
/**
 *
 * @created   by PhpStorm
 * @package   Litebox.php
 * @version   1.1
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date      :     18.06.2015
 * @time      :     3:07
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\Carousel;


/**
 * Class Litebox
 * @package models\Litebox
 */
class Carousel
{

	// путь до папки портфолио
	protected $real_path;
	// изображение картинки - заглушки
	protected $not_foto;
	// изображение вод€ного знака
	protected $watermark;


	/**
	 *
	 */
	public function __construct()
		{
			$this->real_path = SITE_PATH . 'files' . DS . 'portfolio' . DS;
			$this->not_foto = SITE_PATH . 'images/not_foto.png';
			$this->watermark = SITE_PATH . 'images/watermark.png';
		}


	/**
	 * @param             $path
	 * @param bool|string $thumb
	 */
	public function view($path, $thumb = '')
		{

			$image = false;

			if ($path && preg_match('/\.(gif|jpeg|jpg|png)/i', $path)) {

				if (CODE_PAGE === 'windows-1251') {

					$path = mb_convert_encoding($path, 'Windows-1251', 'UTF-8');

				}

				$dirname = $basename = '';
				extract(path_info($path, EXTR_OVERWRITE));
				$this->real_path .= ($thumb === 'thumb') ? $dirname . '/thumb/' . $basename : $dirname . DS . $basename;
				$image = @imagecreatefromstring(@file_get_contents($this->real_path));
			}

			if (!$image) {

				error_log("\$realpath = " . $this->real_path . " \$image = " . $image, 0);
				$image = imagecreatefromstring(file_get_contents($this->not_foto));

			} elseif (!$thumb) {

				$w = imagesx($image);
				$h = imagesy($image);

				$watermark = imagecreatefrompng($this->watermark);
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