<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Portfolio.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     13:02
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\Portfolio;

use models\Base as model;
use proxy\Recursive;


/**
 * Class Portfolio
 * @package models\Portfolio
 */
class Portfolio extends model\Base
{

    /**
     * @param $options
     */
    public function __construct($options = [])
    {
        // настройка свойств класса
        $this->setOptions($options);
        // инициализация конструктора родительского класса
        parent::__construct();

    }

    /**
     *
     */
    public function portfolio()
    {
        Recursive::setExcDir([['thumb']]);
        $thumbdir  = Recursive::scanDir('files/portfolio/', ['jpg'], SCAN_DIR_NAME);
        $i         = 1;
        $portfolio = '';
        if(count($thumbdir)) {

            foreach ($thumbdir as $name => $val) {
                $name = substr($name, 3);
                $name = WinUtf($name, 'w');
                if($i == 1) {
                    ?>
                    <li id="<?='head-' . $i?>" class="text selected"><a href="<?='#tab-' . $i?>"><?=$name?></a></li>
                <? } else { ?>
                    <li id="<?='head-' . $i?>" class="text"><a href="<?='#tab-' . $i?>"><?=$name?></a></li>
                    <?
                }
                $portfolio .= '<div id="tab-' . $i . '" class="tab-content gallery-photo">
							<div class="h-mod">
						<div class="bb-img-red">
                   							<h3 class="h3-2">' . $name . ':</h3></div></div> <div class="inner">
                       						<ul id="mycarousel-' . $i . '" class="jcarousel-skin-tango">';


                for ($n = 0; $n < ceil(count($val) / 4); $n++)
                {
                    $portfolio .= '<li>';
                    for ($m = 0; $m < ((count($val) - $n * 4 < 4) ? count($val) - $n * 4 : 4); $m++)
                    {
                        $patcUtf8 = WinUtf($val[$n * 4 + $m], 'w');
                        $img      = preg_replace('/(\w+\/\w+\/[0-9_a-zа-яёА-ЯЁ]*)/i', '$1/thumb', $patcUtf8);
                        //	$patcUtf8 = urlencode($patcUtf8);
                        $portfolio .= "<a class='plus' href='wm?img={$patcUtf8}'>
                                <img class='thumb' src='{$img}' alt='Фотография из раздела {$name} : " .
                                      basename($img, '.jpg') . '\'></a>';

                    }
                    $portfolio .= '</li>';
                }

                $portfolio .= '</ul></div></div>';
                $i++;
            }


        } else {
            $portfolio = 'Фоторгафий в альбомах пока нет.';
        }
        return $portfolio;
    }

}