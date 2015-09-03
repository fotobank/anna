<?php
/**
 * ����� Index
 *
 * @created   by PhpStorm
 * @package   Index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     29.05.2015
 * @time:     15:05
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\Index;

use Exception;
use modules\Controllers\Controller\Controller;
use modules\Models\Index\Index as ModelIndex;
use view\View;


/**
 * Class controller_Index
 *
 * @package modules\Controllers\Index
 */
class Index extends Controller
{
    /**
     * ������������� �������
     *
     * @param \view\View $view
     *
     */
    public function __construct(View $view)
    {
       $this->viewer = $view;
    }


    /**
     * �����
     *
     * @throws Exception
     */
    public function index()
    {
        $options = [
            // �������� IndexPage
            'http_host'     => getenv('HTTP_HOST'),  // ������� � ��������
            'filenews'      => 'news.txt', // ���� ��������
            'lite_box_path' => 'files/slides/*.jpg' // ����� � ���� ������������ ���������
        ];
        $model = new ModelIndex($options);

        $this->viewer->render('index', $model);
    }

    /**
     * View should be callable
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke()
    {
        $this->index();
    }

    /**
     * Render like string
     *
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        try
        {
            $this->index();
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}