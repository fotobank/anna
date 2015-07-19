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

use classes\pattern\Registry;
use models\Base\Base;


/**
 * Class StubPage
 * @package models\StubPage
 */
class StubPage extends Base
{

    // сообщения и время таймера
    protected $config;

    // почта абонента
    protected $email_abonent;

    // адрес webmaster
    protected $my_email = 'aleksjurii@gmail.com';

    // ошибки для json
    // сообщение абоненту
    public $error = '';
    public $info = '';
    // ответ сервера на запрос о подписке в страницы заглушке
    protected $reply_mess = [];
    // адрес страницы подписки
    protected $str_url;

    // время отсчета таймера
    public $target_day = 30;
    public $target_month = 7;
    public $target_year = 2015;
    public $target_hour = 0;
    public $target_minute = 0;
    public $target_second = 0;

    // часы
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

    // заданные системные сообщения
    protected $messages;

    /**
     * @param array $options
     * @throws \Exception
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

            $this->str_url = $_SERVER['HTTP_REFERER'];

        } catch (\Exception $e) {

            throw new \Exception($e);
        }
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
            'email_exists' => 'Ваш E-mail уже зарегистрирован.',
            'no_email' => 'Пожалуйста, <br/> укажите действующий адрес электронной почты.',
            'email_invalid' => 'Введенный Вами E-mail недействителен. <br/>
                                    Пожалуйста, будьте внимательны при наборе.',
            'thank_you' => 'Спасибо за Ваш интерес к моему сайту!<br/>
                            Как только информация будет подготовленна,<br/>
                             Вы сразу же получите уведомление.',
            'technical' => 'Сервис отправки E-mail временно не работает. <br/>
                            Пожалуйста, попробуйте еще раз позже.',
            'technical_base' => 'Ошибка записи в базу данных. <br/>
                                     Мы уже работаем над этой проблемой <br/>
                                     и в ближайшее время она будет устранена.<br/>
                                     Зайдите, пожалуйста, позже.',
            'bot' => 'Вотам и спамерам подписка запрещена.',
            'no_info' => 'Техничесский сбой. <br/>
                           Мы уже работаем над этой проблемой <br/>
                            и в ближайшее время она будет устранена.<br/>
                           Зайдите, пожалуйста, позже.'
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
        if ($_GET['json']) {

            // проверка e-mail и добавить юзера в базу
            if ($this->checkMail() && $this->addUserToBase()) {

                // отправить почту
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

        // преобразуем массив к utf8
        foreach ($this->reply_mess as $name => &$info) $info = utf8($info);

        return json_encode($this->reply_mess);
    }


    /**
     * проверка введенного email на ошибки
     */
    protected function checkMail()
    {

        if (($_POST['email'])) {

//            $this->email_abonent = utf8_cp1251($_POST['email']);
            $this->email_abonent = mb_convert_encoding($_POST['email'], 'Windows-1251', 'UTF-8');

            if ($this->email_abonent == 'Введите Ваш E-mail') {

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
     * Проверка на наличие такого email и страницы подписи в БД
     * @return bool
     */
    public function checkEmailInDb()
    {


        self::db()->where ('email', $this->email_abonent);
        self::db()->where ('url', $this->str_url );
        $user = self::db()->getOne ('subscribers');

        if ($user) {
            // если есть такой email, то вернет true
            $this->error = $this->messages['email_exists'];
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
            'url' => $this->str_url,
            'ip' => ip()
        ];

        if ($db->insert('subscribers', $data)) {

            return true;
        } else {
            $this->error = $this->messages['technical_base'];
            if (DEBUG) {

                throw new \Exception('Ошибка при передаче данных в базу: ' . $db->getLastError());
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

        return mail($to, $subject, $body, $headers);

    }

}