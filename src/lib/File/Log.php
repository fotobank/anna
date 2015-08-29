<?php

/**
 * log.class.php
 *
 * A generic log file object to use for logging activity to an array or a file.
 *
 * EXAMPLES
 *
 * $log = new Log();
 * $log->write("Log Entry");
 * $log->getLog();
 *
 *
 * $log = new Log("Path/to/file.log");
 * $log->write("log Entry");
 * $log->load();
 * $log->getLog();
 *
 */
namespace lib\File;

use common\Options;

/**
 * Class log
 */
class Log extends File
{

    use Options;

    /**
     * адресс для отправки лога админу
     * сообщение отправляется один раз в день во время создания нового файла лога
     *
     * @var string
     */
    protected $email;
    /**
     * максимально допустимый размер лог директории
     * в килобайтах
     *
     * @var int
     */
    protected $max_dir;
    /**
     * Разрешенный интервал обновления для одной и той же ошибки
     * в минутах
     *
     * @var int
     */
    protected $interval;
    /**
     * новый контент для записилога
     *
     * @var string
     */
    protected $contents = '';
    /**
     * лог из файла в виде строки
     *
     * @var string
     */
    protected $str_log = '';
    /**
     * Последний журнал активности
     * Recent log activity
     *
     * @var array
     */
    protected $log = [];
    protected $glue = PHP_EOL;
    /**
     * Max size of log file MB
     * 1048576 bytes
     */
    protected $max_file_size;

    /**
     * нужен для отмены инициализации класса File
     */
    /** @noinspection PhpMissingParentConstructorInspection */
    /** @noinspection MagicMethodsValidityInspection */
    public function __construct()
    {
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param int $max_dir
     */
    public function setMaxDir($max_dir)
    {
        $this->max_dir = $max_dir;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @param int $max_file_size
     */
    public function setMaxFileSize($max_file_size)
    {
        $this->max_file_size = $max_file_size;
    }

    /**
     * вывод переменной о существовании файла
     *
     * @return bool
     */
    public function isExists()
    {
        return $this->exists;
    }

    /**
     * Пишет строку в файл
     * Если filename не существует, файл будет создан. Иначе, существующий файл будет перезаписан.
     * int putlog( string $contents )
     *
     * @param $filepath
     * @param $contents
     */
    public function putLog($filepath, $contents)
    {
        $this->contents = $contents;
        $this->isFile($filepath);
        $this->getFileLog();
        if($this->checkInterval())
        {
            $this->putContents($this->contents);
        }
    }

    /**
     * Создание файла, если его нет и проверка размера файла
     * если он больше установленного размера - очистка
     * проверка и очистка директории, если размер превышает установленный
     * void filename( string $filename )
     *
     * @param      $filepath
     * @param bool $create
     */
    protected function isFile($filepath, $create = true)
    {
        if((int)($this->total_size) > (int)($this->max_dir))
        {
            $this->clesrDir();
        }
        if($this->size > (1048576 * $this->max_file_size))
        {
            $this->truncate();
        }
        parent::__construct($filepath, $create);
        if(!$this->exists)
        {
            $this->putEmail();
        }
    }

    /**
     * отсылка лога ошибки
     */
    public function putEmail()
    {
        error_log($this->contents, 1, $this->email);
    }

    /**
     * чтение файла
     */
    public function getFileLog()
    {
        $this->str_log = $this->getContents();
        if($this->str_log)
        {
            return true;
        }

        return false;
    }

    /**
     * проверка времени
     * и чтение колличеств данной ошибки
     *
     * @return bool
     */
    protected function checkInterval()
    {
        if('' !== $this->str_log)
        {
            preg_match('/\[(?P<err_num>[\d]+)\]/', $this->str_log, $matches);
            $time_interval = filemtime($this->path) + $this->interval * 60;
            $time_new      = strtotime(date('d-m-Y H:i:s', time()));
            if($time_interval < $time_new)
            {
                $this->contents = '[' . ($matches['err_num'] + 1) . '] ' . $this->contents;
                // прибавление к ошибке единицу
                return true;
            }
            else
            {
                // интервал еще не закончился
                return false;
            }
        }
        else
        {
            $this->contents = '[1] ' . $this->contents; // первая запись
            return true;
        }
    }

    /**
     * запись массива в файл лога и сброс массива
     */
    public function writeLog()
    {
        if(count($this->log) > 0)
        {
            foreach($this->log as $line)
            {
                $this->write(trim($line));
            }
            $this->log = [];
        }
    }

    /**
     * Добавить запись в журнал
     * void write ( string $entry )
     *
     * @param $entry
     */
    public function write($entry)
    {
        $this->log[] = $entry;
        if($this->exists)
        {
            $this->append($this->glue . $entry);
        }
    }

    /**
     * Получение и установка свойств объекта через вызов магического метода вида:
     * $object->(get|set)PropertyName($prop);
     * Properti с большой буквы в CamelCase стиле
     *
     * @param $method_name
     * @param $argument
     *
     * @see __call
     * @return $this|bool|null
     *
     */
    public function __call($method_name, $argument)
    {
        $args          = preg_split('/(?<=\w)(?=[A-Z])/', $method_name);
        $action        = array_shift($args);
        $property_name = strtolower(implode('_', $args));

        switch($action)
        {
            case 'get':
                return isset($this->$property_name) ? $this->$property_name : null;
            case 'set':
                $this->$property_name = $argument[0];

                return $this;
            default:
                return $this;
        }
    }

    /**
     * Get any information contained in the current log
     * Получить любую информацию, содержащуюся в текущем журнале
     * array getLog( void )
     *
     * @param $logFilename
     *
     * @return array
     */
    public function getLog($logFilename)
    {
        $this->isFile($logFilename, false);
        $this->load();

        return $this->log;
    }

    /**
     * Load the log file
     * void filename( void )
     */
    public function load()
    {
        if($this->exists)
        {
            $this->log = $this->toArray();
        }
    }

    /**
     * Set the Glue
     * void setGlue( string $glue )
     *
     * @param $glue
     */
    public function setGlue($glue)
    {
        $this->glue = $glue;
    }


    /**
     * Clear the log, recent activity and the log file will be emptied
     * void emptyLog( void )
     */
    public function emptyLog()
    {
        if($this->exists)
        {
            $this->truncate();
        }
        $this->log = [];
    }

    /**
     * Return Object string
     * string __toString( void )
     */
    public function __toString()
    {
        return implode($this->glue, $this->log);
    }
}