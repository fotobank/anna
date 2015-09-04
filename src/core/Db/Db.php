<?php
/**
 * Класс предназначен для
 *
 * @created   by PhpStorm
 * @package   Db.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     05.08.2015
 * @time:     16:32
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace core\Db;

use exception\Db_Exception;
use lib\Config\Config;

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

    protected $config; // данные для подключения

    /**
     * @param \lib\Config\Config $config
     *
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        try
        {
            $this->config = $config->getData('db');
            self::$prefix = $this->config['prefix'];
            parent::__construct(
                $this->config['host'],
                $this->config['login'],
                $this->config['password'],
                $this->config['db'],
                $this->config['port'],
                $this->config['charset']
            );
        }
        catch(\Exception $e)
        {
            throw $e;
        }

    }

    /**
     * инициализация базы
     *
     * @return $this|bool
     * @throws Db_Exception
     */
    public function init()
    {
        $config = $this->config;
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
     * @param $port
     */
    protected function num_port($port)
    {
        $this->port = ($port == 'default') ? ini_get('mysqli.default_port') : $port;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        if(is_array($config))
        {
            $this->config = $config;
        }
    }
}