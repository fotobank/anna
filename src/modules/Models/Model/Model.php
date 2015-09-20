<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 13.05.2015
 * Time: 22:34
 */

namespace modules\Models\Model;


use lib\Config\Config;
use common\MagicOptions;
use proxy\Db as Db;
use Exception;
use SplSubject;
use SplObserver;
use SplObjectStorage;



/**
 * Class Model
 *
 * @package modules\Models\Model
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Model implements InterfaceModel, SplSubject
{

    use MagicOptions;

    private $storage;

    public $status;

    public $categorii;

    public $current_razdel;

    public $title;

    public $keywords;

    public $description;

    public $admin_mode = false;

    // показывать только заглавную страницу
    public $onluIndex = false;

    // footer
    public $debug_mode = false;

    public $auto_copyright;

    public $php_sessid;

    /** @var array $section_title «аголовки разделов */
    public $section_title;

    /** кнопка login(true) - exit(false) */
    public $login = 0;

    public $http_host;

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \Exception
     * @throws \exception\ModelException;
     */
    public function __construct(Config $config)
    {
        try
        {
            $this->storage = new SplObjectStorage();
            $this->setOptions($config->getData('model'));
            $this->categorii = $this->getCategorii();
            $this->setMetaTitle($config);
        }
        catch(Exception $e)
        {
            throw ($e);
        }

    }

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \lib\Config\ConfigException
     */
    protected function setMetaTitle(Config $config)
    {
        $this->setOptions($config->getData('meta_title', $this->current_razdel));
        $this->title       = cp1251($this->title);
        $this->keywords    = cp1251($this->keywords);
        $this->description = cp1251($this->description);
    }


    /**
     * @param \SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $this->storage->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function detach(SplObserver $observer)
    {
        $this->storage->detach($observer);
    }

    public function notify()
    {
        foreach($this->storage as $obj)
        {
            $obj->update($this);
        }
    }

    /**
     * меню в шапке
     *
     * @return array
     */
    public function globalHeartMenu()
    {
        $razdel = [];
        if($this->current_razdel)
        {
            foreach($this->section_title as $key => $value)
            {
                $razdel[] = [
                    'global_menu_name' => $key,
                    'global_menu_href' => $value,
                    'current'          => ('/' . $this->current_razdel === $value) ? 'current' : '',
                ];
            }
        }

        return $razdel;
    }

    /**
     * @return mixed
     */
    public function getCategorii()
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
     *
     * @return mixed|void
     */
    public function setOnluIndex($onluIndex)
    {
        $this->onluIndex = $onluIndex;
    }
}