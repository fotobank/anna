<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   StubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     16.07.2015
 * @time:     17:01
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\StubPage;

use controllers\Controller\Controller;
use models\StubPage as model;

/**
 * Class StubPage
 * @package controllers\EmailStubPage
 */
class StubPage extends Controller
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
    public function stubPage() {
        $model = new model\StubPage([


        ]);
        echo $this->mustache->render('stubPage', $model);
    }

    /**
     * �����
     *
     * @throws \phpbrowscap\Exception
     */
    public function toEmail() {
        $model = new model\StubPage();
        $mess = $model->toEmail();
        echo $mess;
    }
}