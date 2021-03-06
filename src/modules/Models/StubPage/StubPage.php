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

namespace modules\Models\StubPage;

use modules\Models\Model\Model;
use lib\Config\Config;

use exception\ModelException;
use proxy\Db as Db;


/**
 * Class StubPage
 * @package modules\Models\StubPage
 */
class StubPage extends Model
{
    protected
        $config,  // ��������� � ����� �������
        $email_abonent, // ����� ��������
        $my_email = 'aleksjurii@gmail.com',  // ����� webmaster
        $reply_mess = [],  // ����� ������� �� ajax ������ � �������� � �������� ��������
        $messages = [];  // �������� ��������� ���������

    // ����� ������� �������
    public
        $target_day = 30,
        $target_month = 9,
        $target_year = 2015,
        $target_hour = 0,
        $target_minute = 0,
        $target_second = 0;

    // ����
    public
        $digit_weeks0 = 0,
        $digit_weeks1 = 0,
        $digit_days0 = 0,
        $digit_days1 = 0,
        $digit_hours0 = 0,
        $digit_hours1 = 0,
        $digit_mins0 = 0,
        $digit_mins1 = 0,
        $digit_secs0 = 0,
        $digit_secs1 = 0;

    // ����� - �������� �������
    public
    $weeks = '������',
    $days = '����',
    $hours = '�����',
    $minutes = '�����',
    $seconds = '������';

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \Exception
     * @throws \exception\ModelException
     * @throws \lib\Config\ConfigException
     * @internal param array $options
     */
    public function __construct(Config $config)
    {
        try {
            // ��������� ������� ������
            $this->setOptions($config->getData('services'));
            // ������������� ������������ ������������� ������
            parent::__construct($config);

            $this->messages = $this->getMessages();
            $this->processDate();
        } catch (ModelException $e) {
            throw $e;
        }
    }

    /**
     * Todo setLockPage()
     */
    public function setLockPage()
    {
        $unixTime = $this->getLockTime();
        $date_time = getdate($unixTime);
    }

    /**
     * Todo getLockTime()
     * @return int
     */
    public function getLockTime() {

        $date_time = time();
        return $date_time;
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
            'email_exists' => ['info_msg', '��� E-mail ��� ���������������.'],
            'thank_you' => ['success_msg', '������� �� ��� ������� � ����� �����!<br/>
                            ��� ������ ���������� ����� �������������,<br/>
                             �� ����� �� �������� �����������.'],

            'no_email' => ['error_msg', '����������, ������� ����������� ����� <br/>����������� �����.'],
            'email_invalid' => ['error_msg', '��������� ���� E-mail ��������������. <br/>
                                    ����������, ������ ����������� ��� ������.'],
            'bot' => ['error_msg', '����� � �������� �������� ���������.'],

            'technical' => ['warning_msg', '������ �������� E-mail �������� �� ��������. <br/>
                            ����������, ���������� ��� ��� �����.'],
            'technical_base' => ['warning_msg', '������ ������ � ���� ������. <br/>
                                     �� ��� �������� ��� ���� ��������� <br/>
                                     � � ��������� ����� ��� ����� ���������.<br/>
                                     �������, ����������, �����.'],

            'no_info' => ['warning_msg', '������������ ����. <br/>
                           �� ��� �������� ��� ���� ��������� <br/>
                            � � ��������� ����� ��� ����� ���������.<br/>
                           �������, ����������, �����.']
        ];

    }


    /**
     * �������� �� ���������� ��������
     * @return string
     */
    public function toEmail()
    {
        // json - ������ �� �����
        if(array_key_exists('json', $_GET)) {
            // �������� e-mail � �������� ����� � ����
            if($this->checkMail() && $this->addUserToBase()) {
                // ��������� �����
                $this->sendEmail();
            }
        } else {
            $this->reply_mess = $this->messages['bot'];
        }
        if(count($this->reply_mess) == 0) {
            $this->reply_mess = ('no_info');
        }
        return json_encode(['type' => $this->reply_mess[0], 'msg' => utf8($this->reply_mess[1])]);
    }


    /**
     * �������� ���������� email �� ������ � ������� � ����
     */
    protected function checkMail()
    {

        if((empty($_POST['email']))) {
            $this->reply_mess = $this->messages['no_email'];
            return false;
        }
        $this->email_abonent = mb_convert_encoding($_POST['email'], 'Windows-1251', 'UTF-8');
        if($this->email_abonent == '������� ��� E-mail') {
            $this->reply_mess = $this->messages['no_email'];
            return false;
        }
        if(!filter_var($this->email_abonent, FILTER_VALIDATE_EMAIL)) {
            $this->reply_mess = $this->messages['email_invalid'];
            return false;
        }
        if($this->checkEmailInDb()) {
            return false;
        }
        return true;

    }


    /**
     * �������� �� ������� ������ email � �������� ������� � ��
     * @return bool
     */
    public function checkEmailInDb()
    {
        Db::where('email', $this->email_abonent);
        Db::where('url', $_SERVER['HTTP_REFERER']);
        $user = Db::getOne('subscribers');
        if($user) {
            // ���� ���� ����� email, �� ������ true
            $this->reply_mess = $this->messages['email_exists'];
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
        $data = [
            'email' => $this->email_abonent,
            'subscribed_at' => Db::now(),
            'url' => $_SERVER['HTTP_REFERER'],
            'ip' => ip()
        ];

        if(Db::insert('subscribers', $data)) {

            return true;
        } else {
            $this->reply_mess = $this->messages['technical_base'];
            if(DEBUG_MODE) {
                throw new ModelException('������ ��� �������� ������ � ����: ' . Db::getLastError());
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

        $ret = mail($to, $subject, $body, $headers);

        if($ret) {
            $this->reply_mess = $this->messages['thank_you'];
        } else {
            $this->reply_mess = $this->messages['technical'];
        }

    }

}