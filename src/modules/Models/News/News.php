<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   News.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     13:04
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\News;

use modules\Models\Model\Model;

/**
 * Class News
 * @package modules\Models\News
 */
class News extends Model
{

    /**
     * @param $options
     */
    public function __construct($options = [])
    {
        // ��������� ������� ������
        $this->setOptions($options);
        // ������������� ������������ ������������� ������
        parent::__construct();

    }

}