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

namespace modules\Models\Carousel;

use common\MagicOptions;


/**
 * Class Litebox
 * @package modules\Models\Litebox
 *
 * @method   static string setRealPath($path)
 * @see      modules\Models\Carousel\Carousel::setRealPath($path)
 *
 * @method   static string setNotFoto($path)
 * @see      modules\Models\Carousel\Carousel::setNotFoto()
 *
 * @method   static string setWatermark($path)
 * @see      modules\Models\Carousel\Carousel::setWatermark()
 *
 */
class Carousel
{

	use MagicOptions;

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
			$this->not_foto = SITE_PATH . 'images/not_foto.png';
			$this->watermark = SITE_PATH . 'images/watermark.png';
		}


	/**
	 * @param             $path
	 * @param bool|string $thumb
	 */
	public function view($path)
		{

			$image = false;
			$real_path = $this->real_path;

			if ($path && preg_match('/\.(gif|jpeg|jpg|png)/i', $path)) {

				if (CODE_PAGE === 'windows-1251') {

					$path = mb_convert_encoding($path, 'Windows-1251', 'UTF-8');

				}

				$real_path .= $path;
				if(is_readable($real_path) && filesize($real_path) < MAX_IMG_SIZE)
				{
					$contents = file_get_contents($real_path);
					$image    = imagecreatefromstring($contents);
				}
			}

			if (!$image) {

				error_log('$realpath = ' . $real_path . ' $image = ' . $image, 0);
				$image = imagecreatefromstring(file_get_contents($this->not_foto));

			} elseif(false === stripos($path, 'thumb')) {

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