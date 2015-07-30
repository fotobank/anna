<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Redirect.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     28.07.2015
 * @time:     16:16
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Redirect;

use controllers\Controller\Controller;
use exception\ApplicationException;


/**
 * Class Redirect
 * @package controllers\Redirect
 */
class Redirect extends Controller
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
     * @param string $url
     * @param string $code
     * @internal param string $addr
     */
    public function redirect($url = 'index', $code = '302')
    {
        if(headers_sent()) {
            throw new ApplicationException('��������� ��� �����������.');
        }
        if($url != 'index') {
            if(array_key_exists('HTTP_REFERER', $_SERVER)) {
                header('location: ' . $_SERVER['HTTP_REFERER'], true, $code);
            } else {
                header('location: index', true, $code);
            }
        } else {
            header('location: ' . $url, true, $code);
        }
        exit();
    }
}