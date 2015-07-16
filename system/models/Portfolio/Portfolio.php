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

namespace models\Portfolio;

use models\Base as model;

class Portfolio  extends model\Base
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