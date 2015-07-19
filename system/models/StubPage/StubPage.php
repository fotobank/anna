<?php
/**
 * ����� ������������ ��� �������� �������� �� ���������� ��������
 * @created   by PhpStorm
 * @package   StubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     16.07.2015
 * @time:     17:02
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\StubPage;

use classes\pattern\Registry;
use models\Base\Base;


/**
 * Class StubPage
 * @package models\StubPage
 */
class StubPage extends Base
{

    // ��������� � ����� �������
    protected $config;

    // ����� ��������
    protected $email_abonent;

    // ����� webmaster
    protected $my_email = 'aleksjurii@gmail.com';

    // ������ ��� json
    // ��������� ��������
    public $error = '';
    public $info = '';
    // ����� ������� �� ������ � �������� � �������� ��������
    protected $reply_mess = [];
    // ����� �������� ��������
    protected $str_url;

    // ����� ������� �������
    public $target_day = 30;
    public $target_month = 7;
    public $target_year = 2015;
    public $target_hour = 0;
    public $target_minute = 0;
    public $target_second = 0;

    // ����
    public $digit_weeks0 = 0;
    public $digit_weeks1 = 0;

    public $digit_days0 = 0;
    public $digit_days1 = 0;

    public $digit_hours0 = 0;
    public $digit_hours1 = 0;

    public $digit_mins0 = 0;
    public $digit_mins1 = 0;

    public $digit_secs0 = 0;
    public $digit_secs1 = 0;

    // �������� ��������� ���������
    protected $messages;

    /**
     * @param array $options
     * @throws \Exception
     */
    public function __construct($options = [])
    {
        try {
            // ��������� ������� ������
            $this->setOptions($options);
            // ������������� ������������ ������������� ������
            parent::__construct();

            $this->messages = $this->getMessages();
            $this->processDate();

            $this->str_url = $_SERVER['HTTP_REFERER'];

        } catch (\Exception $e) {

            throw new \Exception($e);
        }
    }

    /**
     * ��������� �����
     */
    protected function processDate()
    {

        $now = time();
        $target = mktime(
            $this->target_hour,
            $this->target_minute,
            $this->target_second,
            $this->target_month,
            $this->target_day,
            $this->target_year
        );

        $diffSecs = $target - $now;

        $date = [];
        $date['secs'] = $diffSecs % 60;
        $date['mins'] = floor($diffSecs / 60) % 60;
        $date['hours'] = floor($diffSecs / 60 / 60) % 24;
        $date['days'] = floor($diffSecs / 60 / 60 / 24) % 7;
        $date['weeks'] = floor($diffSecs / 60 / 60 / 24 / 7);

        foreach ($date as $i => $d) {
            $d1 = $d % 10;
            $d2 = ($d - $d1) / 10;
            $date[$i] = [
                (int)$d2,
                (int)$d1,
                (int)$d
            ];
        }

        $this->digit_weeks0 = $date['weeks'][0];
        $this->digit_weeks1 = $date['weeks'][1];

        $this->digit_days0 = $date['days'][0];
        $this->digit_days1 = $date['days'][1];

        $this->digit_hours0 = $date['hours'][0];
        $this->digit_hours1 = $date['hours'][1];

        $this->digit_mins0 = $date['mins'][0];
        $this->digit_mins1 = $date['mins'][1];

        $this->digit_secs0 = $date['secs'][0];
        $this->digit_secs1 = $date['secs'][1];
    }

    /**
     * @return array
     */
    protected function getMessages()
    {
        return [
            // ��������� �� ������� � �������������� ���������.
            // ������ ��������� ������� �������. " => \"
            'email_exists' => '��� E-mail ��� ���������������.',
            'no_email' => '����������, <br/> ������� ����������� ����� ����������� �����.',
            'email_invalid' => '��������� ���� E-mail ��������������. <br/>
                                    ����������, ������ ����������� ��� ������.',
            'thank_you' => '������� �� ��� ������� � ����� �����!<br/>
                            ��� ������ ���������� ����� �������������,<br/>
                             �� ����� �� �������� �����������.',
            'technical' => '������ �������� E-mail �������� �� ��������. <br/>
                            ����������, ���������� ��� ��� �����.',
            'technical_base' => '������ ������ � ���� ������. <br/>
                                     �� ��� �������� ��� ���� ��������� <br/>
                                     � � ��������� ����� ��� ����� ���������.<br/>
                                     �������, ����������, �����.',
            'bot' => '����� � �������� �������� ���������.',
            'no_info' => '������������ ����. <br/>
                           �� ��� �������� ��� ���� ��������� <br/>
                            � � ��������� ����� ��� ����� ���������.<br/>
                           �������, ����������, �����.'
        ];

    }


    /**
     * �������� �� ���������� ��������
     * @return string
     * @todo �������� ������ ����������� � ���� ������
     */
    public function toEmail()
    {

        // json - ������ �� �����
        if ($_GET['json']) {

            // �������� e-mail � �������� ����� � ����
            if ($this->checkMail() && $this->addUserToBase()) {

                // ��������� �����
                if ($this->sendEmail()) {

                    $this->reply_mess['info'] = $this->messages['thank_you'];

                } else {
                    $this->reply_mess['error'] = $this->messages['technical'];
                }

            } else {
                $this->reply_mess['error'] = $this->error;
            }

        } else {

            $this->reply_mess['error'] = $this->messages['bot'];
        }

        if(count($this->reply_mess) == 0) {

            $this->reply_mess['error'] = ('no_info');
        }

        // ����������� ������ � utf8
        foreach ($this->reply_mess as $name => &$info) $info = utf8($info);

        return json_encode($this->reply_mess);
    }


    /**
     * �������� ���������� email �� ������
     */
    protected function checkMail()
    {

        if (($_POST['email'])) {

//            $this->email_abonent = utf8_cp1251($_POST['email']);
            $this->email_abonent = mb_convert_encoding($_POST['email'], 'Windows-1251', 'UTF-8');

            if ($this->email_abonent == '������� ��� E-mail') {

                $this->error = $this->messages['no_email'];
                return false;

            } elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i",
                $this->email_abonent)) {

                $this->error = $this->messages['email_invalid'];
                return false;

            } elseif($this->checkEmailInDb()) {

                return false;
            } else {

                return true;
            }

        } else {

            $this->error = $this->messages['no_email'];
            return false;
        }
    }


    /**
     * �������� �� ������� ������ email � �������� ������� � ��
     * @return bool
     */
    public function checkEmailInDb()
    {


        self::db()->where ('email', $this->email_abonent);
        self::db()->where ('url', $this->str_url );
        $user = self::db()->getOne ('subscribers');

        if ($user) {
            // ���� ���� ����� email, �� ������ true
            $this->error = $this->messages['email_exists'];
            return true;
        } else {
            // ���� ���� ������ email � �� ������ false, ������� ����� ������ �����
            return false;
        }
    }


    /**
     *
     */
    protected function addUserToBase()
    {
        $db = self::db();
        $data = [
            'email' => $this->email_abonent,
            'subscribed_at' => $db->now(),
            'url' => $this->str_url,
            'ip' => ip()
        ];

        if ($db->insert('subscribers', $data)) {

            return true;
        } else {
            $this->error = $this->messages['technical_base'];
            if (DEBUG) {

                throw new \Exception('������ ��� �������� ������ � ����: ' . $db->getLastError());
            }
            return false;
        }
    }

    /**
     * ��������� e-mail
     * @return bool
     */
    protected function sendEmail()
    {
        $subject = '[����� ������] ����� ������� ';
        $body = '�����������, � ��� ���� ����� �������: ' . '<br />';
        $body .= 'E-mail ��������: ' . $this->email_abonent . '<br />';
        $to = $this->my_email;
        $headers = 'MIME-Version: 1.0' . '\r\n';
        $headers .= 'Content-type: text/html; charset=windows-1251' . '\r\n';

        return mail($to, $subject, $body, $headers);

    }

}