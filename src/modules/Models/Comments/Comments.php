<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Comments.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     14.07.2015
 * @time:     13:08
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\Comments;

use modules\Models\Base\Base;

/**
 * Class Comments
 * @package modules\Models\Comments
 */
class Comments  extends Base
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