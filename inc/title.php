<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 16.07.14
 * Time: 9:54
 */
defined('_SECUR') or die('������ ��������');
/**
 * @return array
 */
function title() {
	/** -----------------------------$razdel ��� �������� �������� ����----------------------------*/
	$razdel = $_SERVER['PHP_SELF'];
	$title  = '';
	if ( $razdel == '/index.php' ) {
		$title = '�������� � ������, ��������� ���������� | ���� ��������� ��������� ����';
		return [ $razdel, $title ];
	} else if ( $razdel == '/about.php' ) {
		$title = '�������� � ������, ��������� ���������� | ���������������� �������� ��������� ����';
		return [ $razdel, $title ];
	} else if ( $razdel == '/portfolio.php' ) {
		$title = '��������� ���� | ���������';
		return [ $razdel, $title ];
	} else if ( $razdel == '/services.php' ) {
		$title = '��������� ���� | ������';
		return [ $razdel, $title ];
	} else if ( $razdel == '/news.php' ) {
		$title = '��������� ���� | �������';
		return [ $razdel, $title ];
	} else if ( $razdel == '/comments.php' ) {
		$title = '��������� ���� | ��������';
		return [ $razdel, $title ];
	}return [ $razdel, $title ];
}