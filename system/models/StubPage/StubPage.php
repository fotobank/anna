<?php
/**
 * Класс предназначен для подписки клиентов на обновление страницы
 * @created   by PhpStorm
 * @package   StubPage.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     16.07.2015
 * @time:     17:02
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace models\StubPage;

use models\Base\Base;
use exception\ModelException;


/**
 * Class StubPage
 * @package models\StubPage
 */
class StubPage extends Base
{
    protected
        $config,  // сообщения и время таймера
        $email_abonent, // почта абонента
        $my_email = 'aleksjurii@gmail.com',  // адрес webmaster
        $reply_mess = [],  // ответ сервера на ajax запрос о подписке в странице заглушке
        $messages = [];  // заданные системные сообщения

    // время отсчета таймера
    public
        $target_day = 30,
        $target_month = 9,
        $target_year = 2015,
        $target_hour = 0,
        $target_minute = 0,
        $target_second = 0;

    // часы
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

    // титры - название времени
    public
    $weeks = 'недель',
    $days = 'дней',
    $hours = 'часов',
    $minutes = 'минут',
    $seconds = 'секунд';

    /**
     * @param array $options
     * @throws ModelException
     */
    public function __construct($options = [])
    {
        try {
            // настройка свойств класса
            $this->setOptions($options);
            // инициализация конструктора родительского класса
            parent::__construct();

            $this->messages = $this->getMessages();
            $this->processDate();
        } catch (ModelException $e) {
            throw $e;
        }
    }

    /**
     *
     */
    public function setLockPage()
    {
        $unixTime = $this->getLockTime();
        $date_time = getdate($unixTime);
    }

    /**
     * @return int
     */
    public function getLockTime() {

        $date_time = time();
        return $date_time;
    }


    /**
     * коррекция часов
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
            // Сообщения об ошибках и информационные сообщения.
            // Всегда избегайте двойных кавычек. " => \"
            'email_exists' => ['info_msg', 'Ваш E-mail уже зарегистрирован.'],
            'thank_you' => ['success_msg', 'Спасибо за Ваш интерес к моему сайту!<br/>
                            Как только информация будет подготовленна,<br/>
                             Вы сразу же получите уведомление.'],

            'no_email' => ['error_msg', 'Пожалуйста, укажите действующий адрес <br/>электронной почты.'],
            'email_invalid' => ['error_msg', 'Введенный Вами E-mail недействителен. <br/>
                                    Пожалуйста, будьте внимательны при наборе.'],
            'bot' => ['error_msg', 'Вотам и спамерам подписка запрещена.'],

            'technical' => ['warning_msg', 'Сервис отправки E-mail временно не работает. <br/>
                            Пожалуйста, попробуйте еще раз позже.'],
            'technical_base' => ['warning_msg', 'Ошибка записи в базу данных. <br/>
                                     Мы уже работаем над этой проблемой <br/>
                                     и в ближайшее время она будет устранена.<br/>
                                     Зайдите, пожалуйста, позже.'],

            'no_info' => ['warning_msg', 'Техничесский сбой. <br/>
                           Мы уже работаем над этой проблемой <br/>
                            и в ближайшее время она будет устранена.<br/>
                           Зайдите, пожалуйста, позже.']
        ];

    }


    /**
     * подписка на обновление страницы
     * @return string
     * @todo доделать запись подписчиков в базу данных
     */
    public function toEmail()
    {
        // json - защита от ботов
        if(array_key_exists('json', $_GET)) {
            // проверка e-mail и добавить юзера в базу
            if($this->checkMail() && $this->addUserToBase()) {
                // отправить почту
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
     * проверка введенного email на ошибки и наличия в базе
     */
    protected function checkMail()
    {

        if((empty($_POST['email']))) {
            $this->reply_mess = $this->messages['no_email'];
            return false;
        }
        $this->email_abonent = mb_convert_encoding($_POST['email'], 'Windows-1251', 'UTF-8');
        if($this->email_abonent == 'Введите Ваш E-mail') {
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
     * Проверка на наличие такого email и страницы подписи в БД
     * @return bool
     */
    public function checkEmailInDb()
    {
        self::db()->where('email', $this->email_abonent);
        self::db()->where('url', $_SERVER['HTTP_REFERER']);
        $user = self::db()->getOne('subscribers');

        if($user) {
            // если есть такой email, то вернет true
            $this->reply_mess = $this->messages['email_exists'];
            return true;
        } else {
            // если нету такого email в БД вернет false, значить можно регить новый
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
            'url' => $_SERVER['HTTP_REFERER'],
            'ip' => ip()
        ];

        if($db->insert('subscribers', $data)) {

            return true;
        } else {
            $this->reply_mess = $this->messages['technical_base'];
            if(DEBUG) {
                throw new ModelException('Ошибка при передаче данных в базу: ' . $db->getLastError());
            }
            return false;
        }
    }

    /**
     * отправить e-mail
     * @return bool
     */
    protected function sendEmail()
    {
        $subject = '[Скоро запуск] Новый абонент ';
        $body = 'Поздравляем, у вас есть новый абонент: ' . '<br />';
        $body .= 'E-mail абонента: ' . $this->email_abonent . '<br />';
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