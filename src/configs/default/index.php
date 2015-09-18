<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     17.09.2015
 * @time:     17:29
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

return
    [
            // �������� IndexPage
            'http_host'     => getenv('HTTP_HOST'),  // ������� � ��������
            'filenews'      => 'news.txt', // ���� ��������
            'lite_box_path' => 'files/slides/*.jpg' // ����� � ���� ������������ ���������
    ];
