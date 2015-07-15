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
 * @time:     13:07
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Comments;


use controllers\Controller as controller;
use models\Comments as model;


/**
 * Class Comments
 * @package controllers\Comments
 */
class Comments  extends controller\Controller
{

    /**
     * ������������� ������� Mustache
     */
    public function __construct()
    {
        parent::init();
    }

    /**
     * �����
     *
     * @throws \phpbrowscap\Exception
     */
    public function comments() {
        $model = new model\Comments();
        echo $this->mustache->render('comments', $model);
    }

}