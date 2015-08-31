<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 22:34
 */

namespace modules\Models\Base;


use proxy\Cookie;
use proxy\Router as Router;
use common\MagicOptions;
use proxy\Db as Db;
use exception\BaseException;
use proxy\Session;
use Exception;



/**
 * Class BaseModelsException
 * @package modules\Models\Base
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class BaseModelsException extends BaseException
{
}


/**
 * Class Base
 * @package modules\Models\Base
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Base implements InterfaceModelsBase
{

    use MagicOptions;

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
            $this->file_meta_title = ROOT_PATH . 'configs/meta_title.ini';
            $this->admin_mode = if_admin(true);
            // footer
            $this->debug_mode = DEBUG_MODE;
            $this->auto_copyright = auto_copyright('2011');
            $this->php_sessid = Cookie::get('PHPSESSID') or ip();
            $this->categorii = $this->getDbTitleName();

    //   $this->current_razdel = array_key_exists(0, Router::getUrlRoutes()) ? Router::getUrlRoutes()[0] : null;
            $this->getMetaTitle();
            // кнопка login
            $this->login = Session::get('logged');
        } catch (BaseModelsException $e) {
            throw ($e);
        }

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