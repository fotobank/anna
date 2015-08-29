<?php
/**
 *
 * @created   by PhpStorm
 * @package   error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     29.08.2015
 * @time:     13:13
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

return
[
        // �������� - ��������
        'friendlyExceptionPage' => 'stop',
        // ������� ����������� ����
        'logType'               => 'detail',
        // false / simple / detail
        'logDir'                => '',
        // ���������� ����������� ��������������
        'variables'             => ['_GET', '_POST', '_SESSION', '_COOKIE'],
        // ���� ������������ ������
        'ignoreERROR'           => [],
        // �������� �������� ��� ��� ������� ����� ������� ( �.�. �� '127.0.0.1' ��� �������� )
        'log_on'                => true
];