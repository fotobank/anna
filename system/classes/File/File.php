<?php
/*
 * file.class.php
 */

namespace classes\File;
/**
 * Class file
 */
class File
{

    // The directory name where the file is located
    protected $dirname;

    // The basename of the file filename plus extension with out the directory
    protected $basename;

    // The file extension
    protected $extension;

    // The filename with out the extension
    protected $name;

    // The full path of file, including the directory
    protected $path;

    // файл существует и успешно создан
    protected $exists = false;

    // Is the file writable flag
    protected $is_writable = false;

    // Is the directory the file is in writable flag
    protected $is_dir_writable = false;

    // Is the file readable flag
    protected $is_readable = false;

    // The size of the file in bytes
    protected $size = 0;

    // размер директории в колобайтах
    protected $total_size = 0;


    /**
     * @param      $filepath
     * @param bool $create
     */
    public function __construct($filepath, $create = true)
    {
        if (($filepath)) {
            $info = pathinfo($filepath);
            $this->dirname = $info['dirname'];
            $this->basename = $info['basename'];
            $this->extension = array_key_exists('extension', $info) ? $info['extension'] : '';
            $this->name = $info['filename'];
            $this->path = $filepath;
        } else {
            throw new \Exception ('Не указано имя и путь к файлу!', E_USER_NOTICE);
        }
        /**
         * Если файл не существует пробуем создать его
         */
        if (!is_readable($filepath) && $this->checkfolder() && $create && !touch($filepath) && DEBUG_MODE) {

            trigger_error('Невозможно создать файл!', E_USER_ERROR);
        }

        // Set some flags about this file
        $this->exists = file_exists($filepath);
        $this->is_writable = is_writable($filepath);
        $this->is_dir_writable = is_writable($this->dirname);
        $this->is_readable = is_readable($filepath);

        // if the file exists, get the size
        if ($this->exists === TRUE) {
            $this->size = filesize($filepath);
            $this->total_size = disk_total_space($this->dirname) / 1024;
        }
    }

    /**
     * Magic Method
     * mixed __get( string $prop )
     * @param $prop
     * @return bool
     */
    public function __get($prop)
    {
        if (isset($this->$prop)) {
            return $this->$prop;
        } else {
            trigger_error("Property '$prop' does not exist");
        }
        return false;
    }

    /**
     * очистка директории
     */
    protected function clesr_dir()
    {
        if (is_dir($this->dirname) && is_writable($this->dirname)) {
            $files = glob($this->dirname . '/*');
            if ($files) {
                foreach ($files as $file) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * @return bool
     */
    protected function checkfolder()
    {
        if (null !== ($this->dirname) && !is_dir($this->dirname)) {
            if (mkdir($this->dirname, 0777)) {
                return true;
            } else {
                trigger_error("Нет прав для создания католога {$this->dirname}", E_USER_ERROR);
                return false;
            }
        } elseif (is_dir($this->dirname) && !is_writable($this->dirname)) {
            if (chmod($this->dirname, 0777)) {
                return true;
            } else {
                trigger_error("Нет прав для изменения атрибутов католога. Каталог {$this->dirname} не доступен для записи.", E_USER_ERROR);
                return false;
            }
        } elseif (null === ($this->dirname)) {
            trigger_error("Не указан каталог {$this->dirname}", E_USER_ERROR);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Remove all contents of the file, Empty it
     * Удалите все содержимое файла, освободить его
     * void truncate( void )
     */
    public function truncate()
    {
        if ($this->is_writable) {
            $dh = fopen($this->path, 'w');
            fclose($dh);
        } else {
            trigger_error('Невозможно очистить незаписываемый файл');
        }
    }

    /**
     * Write data at the end of the file
     * Записать данные в конец журнала
     * void append( string $data )
     * @param $data
     */
    public function append($data)
    {
        if ($this->is_writable) {
            file_put_contents($this->path, $data, FILE_APPEND | LOCK_EX);
        } else {
            trigger_error('Can not append a file that is not writable');
        }
    }

    /**
     * Return the file as an array
     * array to_array( int $flags = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES )
     * @param null $flags
     * @return array
     */
    public function to_array($flags = null)
    {
        $arr = [];
        if ($this->is_readable) {
            if (null === ($flags)) {
                $arr = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            } else {
                $arr = file($this->path);
            }

        }
        return $arr;
    }

    /**
     * Пишет строку в файл
     * Если filename не существует, файл будет создан. Иначе, существующий файл будет перезаписан.
     * int put_contents( string $contents )
     * @param $contents
     * @return bool|int
     */
    public function put_contents($contents)
    {
        if ($this->is_writable) {
            return file_put_contents($this->path, $contents, LOCK_EX);
        } else {
            trigger_error('Can not write contents to an unwritable file');
        }
        return false;
    }

    /**
     * File get Contents,
     * string get_contents( void )
     */
    public function get_contents()
    {
        if ($this->is_readable) {
            return file_get_contents($this->path);
        } else {
            trigger_error('Can not read the file.');
        }
        return false;
    }

    /**
     * Magic Method __toString()
     * string __toString( void )
     */
    public function __toString()
    {
        if (!$this->is_readable) {

            trigger_error('Can not return a string for a unreadable file.');
        }
        return file_get_contents($this->path);
    }
}