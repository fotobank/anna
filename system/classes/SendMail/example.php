<?php
/**
 * @created   by PhpStorm
 * @package   example.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     14.05.2015
 * @time:     15:58
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


require_once(__DIR__.'/SendMail.php');

SendMail::from('admin@mail.ru', '�����')  // ����� � ��� �����������.
// ������ �������� �� ����������.

		 ->to('user@mail.ru', '����')  /* ����� � ��� ��������
                                           (����� ������ �������).

                                       $toUsers = array(
                                           array('user@mail.ru', '�������'),
                                           array('user2@mail.ru', '������')
                                       );

                                       ���

                                       $toUsers = array('user@mail.ru',
                                           'user2@mail.ru');

                                       */


// ���� ���������.
		 ->subject('���� ���������')

	// ���� ���������.
		 ->message('���� ���������')

	// ���� �� �������������� ����� (����� ������).
		 ->files(__DIR__ . '/files/image.jpg')

	// ����������. �� ��������� false.
		 ->notify(true)

	// ��������� ������. True, ���� ������. �� ��������� false.
		 ->important(true)

	// ��������� (�� ��������� utf-8)
		 ->charset('utf-8')

	// set_time_limit (�� ��������� == 30�.)
		 ->time_limit(30)

	// ��� ��������� (�� ��������� text/plain)
		 ->content_type(SendMail::CONTENT_TYPE_PLAIN)

	// ��� ����������� ��������� (�� ��������� 'quoted-printable').
		 ->content_encoding(SendMail::CONTENT_ENCODING_QUOTED_PRINTTABLE)

	// �������� �����
		 ->send();