<?php
/**
 *  ласс предназначен дл€
 *
 * @created   by PhpStorm
 * @package   Db.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     05.08.2015
 * @time:     16:32
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace core\Db;

use exception\Db_Exception;

/** @noinspection RealpathOnRelativePathsInspection */
defined('SITE_PATH') or define('SITE_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                                                     DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);


/**
 * Class Db
 *
 * @package core\Db
 */
class Db extends MysqliDb
{

    protected $_transaction_in_progress; // добавил не была объ€вленна
    protected $file_pass = 'system/config/db_config.php'; // данные дл€ подключени€

    /**
     * инициализаци€ базы
     *
     * @return $this|bool
     * @throws Db_Exception
     */
    public function init()
    {
        $config         = $this->getParam();
        if(!is_array($config))
        {
            throw new Db_Exception('ошибка в файле конфигурации база данных', 500);
        }
        // if params were passed as array
        if(is_array($config['host']))
        {
            foreach($config['host'] as $key => $val)
            {
                $$key = $val;
            }
        }
        // if host were set as mysqli socket
        if(is_object($config['host']))
        {
            $this->_mysqli = $config['host'];
        }
        else
        {
            $this->host = $config['host'];
        }
        $this->username = $config['login'];
        $this->password = $config['password'];
        $this->db       = $config['db'];
        $this->charset  = $config['charset'];
        $this->num_port($config['port']);

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if(isset($config['prefix']))
        {
            $this->setPrefix($config['prefix']);
        }
        $this->connect();
    }

    /**
     * @param string $file
     *
     * @return array
     * @throws Db_Exception
     */
    public function getParam($file = '')
    {

        if($file != '' && is_file($file)) {
            $this->file_pass = $file;
        }
        if(is_readable(SITE_PATH . $this->file_pass)) {

            /** @noinspection PhpIncludeInspection */
            return require_once(SITE_PATH . $this->file_pass);

        } else {
            throw new Db_Exception('не найден файл с параметрами подключени€.', 404);
        }
    }

    /**
     * @param $port
     */
    protected function num_port($port)
    {
        $this->port = ($port == 'default') ? ini_get('mysqli.default_port') : $port;
    }

}