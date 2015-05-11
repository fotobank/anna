<?php

/*
 * log.class.php
 * 
 * A generic log file object to use for logging activity to an array or a file.
 * 
 * EXAMPLES
 * 
 * $log = new log();
 * $log->write("Log Entry");
 * $log->get_log();
 * 
 * 
 * $log = new log("Path/to/file.log");
 * $log->write("log Entry");
 * $log->load();
 * $log->get_log();
 * 
 */

/**
 * Class log
 */
class Log extends File {

	/**
	 * адресс для отправки лога админу
	 * сообщение отправляется один раз в день во время создания нового файла лога
	 * @var string
	 */
	protected $email = 'aleksjurii@gmail.com';
	/**
	 * максимально допустимый размер лог директории
	 * в килобайтах
	 * @var int
	 */
	protected $max_dir = 10000;
	/**
	 * Разрешенный интервал обновления для одной и той же ошибки
	 * в минутах
	 * @var int
	 */
	protected $interval = 5; // мин
	/**
	 * новый контент для записилога
	 * @var string
	 */
	protected $contents = '';
	/**
	 * лог из файла в виде строки
	 * @var string
	 */
	protected $str_log = '';
	/**
	 * Последний журнал активности
	 * Recent log activity
	 * @var array
	 */
	protected $log = [ ];

	// The End of line Glue
	protected $glue = PHP_EOL;

	// Max size of log file MB
	// 1048576 bytes
	protected $max_file_size = 1;

	/**
	 * @param null $filepath
	 */
	public function __construct( $filepath = NULL ) {
		if ( isset( $filepath ) ) {
			 $this->is_file( $filepath );
		}
	}

	/**
	 * вывод переменной о существовании файла
	 * @return bool
	 */
	public function exists() {
		return $this->exists;
	}

	/**
	 * Добавить запись в журнал
	 * void write ( string $entry )
	 */
	public function write( $entry ) {
		$this->log[] = $entry;
		if ( $this->exists ) {
			$this->append( $this->glue . $entry );
		}
	}

	/**
	 * Пишет строку в файл
	 * Если filename не существует, файл будет создан. Иначе, существующий файл будет перезаписан.
	 * int putlog( string $contents )
	 */
	public function put_log( $filepath, $contents ) {
		$this->contents = $contents;
		$this->is_file( $filepath );
		$this->get_file_log();
		if ( $this->checkInterval()) $this->put_contents( $this->contents );
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
	public function is_file( $filepath, $create = TRUE ) {
		if ( (int) ( $this->total_size ) > (int) ( $this->max_dir ) ) {
			$this->clesr_dir();
		}
		if ( $this->size > ( 1048576 * $this->max_file_size ) ) {
			$this->truncate();
		}
		parent::__construct( $filepath, $create );
		if ( ! $this->exists ) {
			$this->put_email();
		}
	}

	/**
	 * запись массива в файл лога и сброс массива
	 */
	public function write_log() {
		if ( count( $this->log ) > 0 ) {
			foreach ( $this->log as $line ) {
				$this->write( trim( $line ) );
			}
			$this->log = [ ];
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
	public function __call( $method_name, $argument ) {
		$args          = preg_split( '/(?<=\w)(?=[A-Z])/', $method_name );
		$action        = array_shift( $args );
		$property_name = strtolower( implode( '_', $args ) );

		switch ( $action ) {
			case 'get':
				return isset( $this->$property_name ) ? $this->$property_name : null;
			case 'set':
				$this->$property_name = $argument[0];
				return $this;
			default:
				return $this;
		}
	}

	/**
	 * отсылка лога ошибки
	 */
	public function put_email() {
		error_log( $this->contents, 1, $this->email );
	}

	/**
	 * Get any information contained in the current log
	 * Получить любую информацию, содержащуюся в текущем журнале
	 * array get_log( void )
	 */
	public function get_log( $logFilename ) {
		$this->is_file( $logFilename, false );
		$this->load();
		return $this->log;
	}

	/**
	 * чтение файла
	 */
	public function get_file_log() {
		$this->str_log = $this->get_contents();
		if ( $this->str_log ) {
			return true;
		}
		return false;
	}

	/**
	 * проверка времени
	 * и чтение колличеств данной ошибки
	 * @return bool
	 */
	public function checkInterval() {

		if ( strlen( $this->str_log ) ) {
			preg_match('/\[(?P<err_num>[\d]+)\]\s*(?P<date_old>[\d-]+)\s(?P<time_old>[\d:]+)/', $this->str_log, $matches);
				if ( strtotime( $matches['date_old'].' '.$matches['time_old'] ) + $this->interval * 60 - strtotime( date( 'd-m-Y H:i:s', time() ) ) < 0 ) {
					$this->contents = "[".($matches['err_num']+1)."] ".$this->contents;
					return true; // прибавление к ошибке единицу
				} else {
					return false; // интервал еще не закончился
				}
		} else {
			$this->contents = "[1] ".$this->contents; // первая запись
			return true;
		}
	}

	/**
	 * Set the Glue
	 * void setglue( string $glue )
	 */
	public function setglue( $glue ) {
		$this->glue = $glue;
	}

	/*
	 * Clear the log, recent activity and the log file will be emptied
	 * void empty_log( void )
	 */
	public function empty_log() {
		if ( $this->exists ) {
			$this->truncate();
		}
		$this->log = [ ];
	}

	/**
	 * Return Object string
	 * string __toString( void )
	 */
	public function __toString() {
		return implode( $this->glue, $this->log );
	}

	/**
	 * Load the log file
	 * void filename( void )
	 */
	public function load() {
		if ( $this->exists ) {
			$this->log = $this->to_array();
		}
	}
}