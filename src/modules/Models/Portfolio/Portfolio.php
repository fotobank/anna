<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Portfolio.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     13:02
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\Portfolio;

use modules\Models\Model\Model;
use lib\Config\Config;
use proxy\Recursive;


/**
 * Class Portfolio
 * @package modules\Models\Portfolio
 */
class Portfolio extends Model
{

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \Exception
     * @throws \lib\Config\ConfigException
     * @throws \modules\Models\Model\ModelException
     */
    public function __construct(Config $config)
    {
        try
        {
            // ��������� ������� ������
            $this->setOptions($config->getData('portfolio'));
            // ������������� ������������ ������������� ������
            parent::__construct($config);
        }
        catch(\Exception $e)
        {
            throw $e;
        }

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
                        $img      = preg_replace('/(\w+\/\w+\/[0-9_a-z�-���-ߨ]*)/i', '$1/thumb', $patcUtf8);
                        //	$patcUtf8 = urlencode($patcUtf8);
                        $portfolio .= "<a class='plus' href='wm?img={$patcUtf8}'>
                                <img class='thumb' src='{$img}' alt='���������� �� ������� {$name} : " .
                                      basename($img, '.jpg') . '\'></a>';

                    }
                    $portfolio .= '</li>';
                }

                $portfolio .= '</ul></div></div>';
                $i++;
            }


        } else {
            $portfolio = '���������� � �������� ���� ���.';
        }
        return $portfolio;
    }

}