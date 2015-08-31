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
 * @time:     12:59
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\News;

use modules\Controllers\Controller\Controller;
use modules\Models\News as model;


/**
 * Class News
 * @package modules\Controllers\News
 */
class News  extends Controller
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
    public function news() {
        $model = new model\News();
        return $this->mustache->render('news', $model);
    }

}