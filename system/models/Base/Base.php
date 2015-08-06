<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 22:34
 */


namespace models\Base;


use proxy\Cookie;
use proxy\Router as Router;
use common\Container\Options;
use proxy\Db as Db;
use exception\BaseException;
use proxy\Session;
use Exception;



/**
 * Class BaseModelsException
 * @package models\Base
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class BaseModelsException extends BaseException
{
    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Construct the exception. Note: The message is NOT binary safe.
     * @link http://php.net/manual/en/exception.construct.php
     * Fix for https://github.com/facebook/hhvm/blob/HHVM-3.4.0/hphp/system/php/lang/Exception.php#L55
     *
     * @param string    $message  [optional] The Exception message to throw.
     * @param int       $code     [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        $numAgs = func_num_args();
        if ($numAgs >= 1) {
            $this->message = $message;
        }

        if ($numAgs >= 2) {
            $this->code = $code;
        }
        parent::__construct($this->message, $this->code, $previous);
    }
}


/**
 * Class Base
 * @package models\Base
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Base implements InterfaceModelsBase
{

    use Options;

    public $categorii;

    public $current_razdel;

    public $title;

    public $keywords;

    public $description;

    public $admin_mode = false;

    public $file_meta_title;

    // показывать только заглавную страницу
    public $onluIndex = false;

    // footer
    public $debug_mode = false;

    public $auto_copyright;

    public $php_sessid;

    public $section_title = [
        'Главная' => '/index',
        'Об&nbsp;&nbsp;авторе' => '/about',
        'Портфолио' => '/portfolio',
        'Новости' => '/news',
        'Услуги' => '/services',
        'Гостевая' => '/comments'
    ];

    // кнопка login(true) - exit(false)
    public $login = 0;

    public $http_host;

    /**
     *
     */
    public function __construct()
    {
        try {
            $this->file_meta_title = SITE_PATH . 'system/config/meta_title.ini';
            $this->admin_mode = if_admin(true);
            // footer
            $this->debug_mode = DEBUG_MODE;
            $this->auto_copyright = auto_copyright('2011');
            $this->php_sessid = Cookie::get('PHPSESSID') or ip();
            $this->categorii = $this->getDbTitleName();
            $this->current_razdel = Router::getUrlRoutes()[0] or null;
            $this->getMetaTitle();
            // кнопка login
            $this->login = Session::get('logged');
        } catch (BaseModelsException $e) {
            throw ($e);
        }

    }

    /**
     *  проверка времени блокировки страницы
     * @param $url
     * @return array
     */
    public function checkClockLockPage($url)
    {
        Db::where('url', $url);
        $lock = Db::getOne('lock_page');

        if(null !== count($lock)) {
            $difference_time = $lock['end_date'] - time();
            if($difference_time > 0) {
                // если время таймера не вышло показывать страницу - заглушку
                return $lock;
            }
            if($lock['auto_run'] === 1) {
                // загрузить обычную страницу
                return false;
            } else {
                return $lock;
            }
        }
        // если записи нет загрузить обычную страницу
        return false;
    }


    /**
     * присвоение значений переменным metatitle в шапке
     * @return bool
     * @throws BaseModelsException
     */
    public function getMetaTitle()
    {
        try {
            if(is_file($this->file_meta_title)) {
                $arrayMetaTitle = parse_ini_file($this->file_meta_title, true);
                foreach ($arrayMetaTitle as $title => $metaData) {

                    if('/' . $title . '.php' === $this->current_razdel) {
                        $this->title = $metaData['title'];
                        $this->keywords = $metaData['keywords'];
                        $this->description = $metaData['description'];
                    }
                }
                if($this->onluIndex) {
                    $this->current_razdel = false;
                }
                return true;
            }
            throw new BaseModelsException('не найден ini файл => ' . $this->file_meta_title);
        } catch (BaseModelsException $e) {
            throw $e;
        }
    }

    /**
     * меню в шапке
     * @return array
     */
    public function globalHeartMenu()
    {
        $razdel = [];
        if($this->current_razdel) {
            foreach ($this->section_title as $key => $value) {
                $razdel[] = [
                    'global_menu_name' => $key,
                    'global_menu_href' => $value,
                    'current' => ('/' . $this->current_razdel === $value) ? 'current' : ''
                ];
            }
        }
        return $razdel;
    }

    /**
     * @return mixed
     */
    public function getDbTitleName()
    {
        Db::orderBy('position', 'ASC');
        return Db::get('index_menu', null, ['id', 'name_head']);
    }

    /**
     * A helper method to sanitize a string:
     *
     * @param $text
     *
     * @return mixed|string
     */
    public function esc($text)
    {
        $text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));
        return $text;
    }

    /**
     * показывать только заглавную страницу
     *
     * @param boolean $onluIndex
     * @return mixed|void
     */
    public function setOnluIndex($onluIndex)
    {
        $this->onluIndex = $onluIndex;
    }
}