<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 11.07.14
 * Time: 13:01
 */

use classes\pattern\Proxy\Db as Db;


/**
 * Class Citates
 */
class Citates {

	public $citata = ''; // вывод одной найденной фразы
	public  $avtor = ''; // одного из авторов

	private $url = 'http://birdwatcher.ru/quotes/'; // адресс сайта донора
	private $quote = '.quote'; // блок класса состояший из блока цитаты и блока автора
	private $quote__citata = '.quote__text'; // блок цитаты
	private $quote__avtor = '.quote__meta'; // длок автора
	private $upd_db = false; // true - обновить принудительно (для настройки)
	private $time = false; // интервал обновления базы в днях (false - взять из базы, если задан - записать в базу)
	private $html = ''; // часть страницы сайта донора
	private $htmlCit = ''; // вырезанный блок
	private $error = ''; // ошибки скрипта
	private $id_pick = false; // идентификатор записи
	private $tabl = false; // текущая таблица


	/**
	 * @param $tune
	 *
	 * @throws \Exception
	 */
	function __construct( $tune ) {

		$this->_get_var( $tune ); // обновление исходных данных

		if ( Db::init() ) {
			/** @noinspection PhpIncludeInspection */
			require_once( SITE_PATH . 'system/classes/SimpleHtmlDomNode.php' ); // подключение парсера
			$this->checkUpd();
			$this->randCitata();
		} else {
			$this->error .= 'не подключенна база данных \n'. Db::getLastError();
			throw new \Exception('не подключенна база данных \n' . Db::getLastError(), E_USER_ERROR );
		}
	}

	/**
	 * @param $tune
	 */
	private function _get_var( $tune ) {
		if ( count( $tune ) ) {
			foreach ( $tune as $name => $data ) {
				$this->$name = $data;
			}
		}
	}

	private function checkUpd() {

		$data = Db::get( 'pick' );
// debugHC( $data, "data" );
		if ( Db::getCount() > 0 ) {
			foreach ( $data as $rec ) {
				$this->url           = $rec['url']; // адресс сайта донора
				$this->quote         = $rec['quote']; // блок класса состояший из блока цитаты и блока автора
				$this->quote__citata = $rec['q_citata']; // блок цитаты
				$this->quote__avtor  = $rec['q_avtor']; // длок автора
				$this->tabl          = $rec['tabl']; // текущая таблица
				$this->id_pick       = $rec['id']; // идентификатор записи
				$this->time          = ( isset( $this->time ) && intval( $this->time ) > 0 ) ? $this->time : $rec['time'];
				if ( $this->upd_db or $this->checkInterval( $rec['date'], $this->time ) ) $this->parser();
			}
		} else {
			$this->error .= " обновление не выполненно, в базе нет данных для парсинга донора \n" . Db::getLastError() ;
			trigger_error("обновление не выполненно, в базе нет данных \n" . Db::getLastError() , E_USER_ERROR );
		}
	}

	/**
	 * проверка времени обновления
	 *
	 * @param $date
	 * @param $time
	 *
	 * @return bool
	 */
	private function checkInterval( $date, $time ) {
		return ( date_diff( new DateTime(), new DateTime( $date ) )->days > $time ) ? true : false;
	}

	/**
	 * парсер участка страницы
	 */
	private function parser() {
		if ( $this->_get_html() && $this->_parser_html() ) {

			foreach ( $this->html->find( $this->quote ) as $data ) {
				$this->htmlCit = str_get_html( urldecode( $data->innertext ) );
				$citata        = '';
				$avtor         = '';
				if ( $this->htmlCit && $this->htmlCit->innertext != '' and count( $this->htmlCit->find( $this->quote__citata ) ) ) {
					$cit    = $this->htmlCit->find( $this->quote__citata );
					$citata = preg_replace( "![\\x00-\\x1F]!s", '', $cit[0]->plaintext );
				}
				if ( $this->htmlCit && $this->htmlCit->innertext != '' and count( $this->htmlCit->find( $this->quote__avtor ) ) ) {
					$avt   = $this->htmlCit->find( $this->quote__avtor );
					$avtor = preg_replace( "![\\x00-\\x1F]!s", '', $avt[0]->innertext );
				}
				if ( $this->htmlCit ) {
					$this->htmlCit->clear(); // подчищаем за собой
					unset( $this->htmlCit );
				}
				$this->push( $citata, $avtor );
			}
		}
		if( strlen($this->error) == 0 ) {
			$this->pick_upd( 1 );
		} else {
			$this->pick_upd( 0 );
		}
	}


	/**
	 * чтение контента страницы
	 * @return bool
	 */
	private function _get_html() {
		if ( $this->html = @file_get_html( $this->url ) ) {
			return true;
		}
		if ( $this->id_pick ) {
			$this->error .= ' запись в базе не обновленна, не доступен донор \n'. Db::getLastError();
			trigger_error('запись в базе не обновленна, не доступен донор'. Db::getLastError() , E_USER_ERROR );
		} else {
			$this->error .= ' запись в базе не обновленна, нет идентификатора id \n'. Db::getLastError();
			trigger_error('запись в базе не обновленна, нет идентификатора id'. Db::getLastError() , E_USER_ERROR );
		}
		return false;
	}

	/**
	 * разбор результата по заданным параметрам
	 * @return bool
	 */
	private function _parser_html() {
		if ( $this->html && $this->html->innertext != '' && count( $this->html->find( $this->quote ) ) ) {
			return true;
		}
		$this->error .= ' запись в базе не обновленна. Нет контента, удовлетворяющего условиям парсинга \n'.
						Db::getLastError();
		trigger_error('запись в базе не обновленна. Нет контента, удовлетворяющего условиям парсинга' .
					  Db::getLastError(), E_USER_ERROR );
		return false;
	}

	/**
	 *  запись фраз в базу данных
	 *
	 * @param $citata
	 * @param $avtor
	 *
	 * @return int
	 */
	private function push( $citata, $avtor ) {
		$data = [
				'cit'     => "$citata",
				'hash'    => Db::func( 'MD5(?)', ["$citata"]),
				'avtor'   => "$avtor",
				'id_pick' => "$this->id_pick"
		];
		Db::insert( 'citata', $data, 'IGNORE' );
	}

	/**
	 * запись даты последнего обновления и статуса операции
	 *
	 * @param $ok
	 */
	private function pick_upd( $ok ) {
		$data = [
				'suss' => $ok,
				'time' => $this->time,
				'date' => date( 'Y-m-d H:i:s' ), // пример: 2014-07-14 14:31:58
				'err'  => $this->error
		];

		Db::where( 'id', $this->id_pick );
		Db::update( 'pick', $data );
	}

	/**
	 *  выводит одну случайную цитату из базы
	 */
	public function randCitata() {

		$rand = Db::orderBy( 'RAND()' )->get( 'citata', '1', 'cit, avtor' ); // "SELECT cit,avtor FROM citata ORDER BY RAND() LIMIT 1"
		if ( count($rand) > 0 ) {
			$this->citata = urldecode( $rand [0]['cit'] );
			$this->avtor  = urldecode( $rand [0]['avtor'] );
		} else {
			$this->citata = 'Мир просто не помещается в формат 35-мм камеры.'; // цитата по умолчанию.
			$this->avtor  = 'W. Eugene Smith';
		}
	}

	function __destruct() {

		if ( $this->html ) {
			$this->html->clear(); // подчищаем за собой
			unset( $this->html );
		}
	}

}