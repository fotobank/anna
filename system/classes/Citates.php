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

	public $citata = ''; // ����� ����� ��������� �����
	public  $avtor = ''; // ������ �� �������

	private $url = 'http://birdwatcher.ru/quotes/'; // ������ ����� ������
	private $quote = '.quote'; // ���� ������ ��������� �� ����� ������ � ����� ������
	private $quote__citata = '.quote__text'; // ���� ������
	private $quote__avtor = '.quote__meta'; // ���� ������
	private $upd_db = false; // true - �������� ������������� (��� ���������)
	private $time = false; // �������� ���������� ���� � ���� (false - ����� �� ����, ���� ����� - �������� � ����)
	private $html = ''; // ����� �������� ����� ������
	private $htmlCit = ''; // ���������� ����
	private $error = ''; // ������ �������
	private $id_pick = false; // ������������� ������
	private $tabl = false; // ������� �������


	/**
	 * @param $tune
	 *
	 * @throws \Exception
	 */
	function __construct( $tune ) {

		$this->_get_var( $tune ); // ���������� �������� ������

		if ( Db::init() ) {
			/** @noinspection PhpIncludeInspection */
			require_once( SITE_PATH . 'system/classes/SimpleHtmlDomNode.php' ); // ����������� �������
			$this->checkUpd();
			$this->randCitata();
		} else {
			$this->error .= '�� ����������� ���� ������ \n'. Db::getLastError();
			throw new \Exception('�� ����������� ���� ������ \n' . Db::getLastError(), E_USER_ERROR );
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
				$this->url           = $rec['url']; // ������ ����� ������
				$this->quote         = $rec['quote']; // ���� ������ ��������� �� ����� ������ � ����� ������
				$this->quote__citata = $rec['q_citata']; // ���� ������
				$this->quote__avtor  = $rec['q_avtor']; // ���� ������
				$this->tabl          = $rec['tabl']; // ������� �������
				$this->id_pick       = $rec['id']; // ������������� ������
				$this->time          = ( isset( $this->time ) && intval( $this->time ) > 0 ) ? $this->time : $rec['time'];
				if ( $this->upd_db or $this->checkInterval( $rec['date'], $this->time ) ) $this->parser();
			}
		} else {
			$this->error .= " ���������� �� ����������, � ���� ��� ������ ��� �������� ������ \n" . Db::getLastError() ;
			trigger_error("���������� �� ����������, � ���� ��� ������ \n" . Db::getLastError() , E_USER_ERROR );
		}
	}

	/**
	 * �������� ������� ����������
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
	 * ������ ������� ��������
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
					$this->htmlCit->clear(); // ��������� �� �����
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
	 * ������ �������� ��������
	 * @return bool
	 */
	private function _get_html() {
		if ( $this->html = @file_get_html( $this->url ) ) {
			return true;
		}
		if ( $this->id_pick ) {
			$this->error .= ' ������ � ���� �� ����������, �� �������� ����� \n'. Db::getLastError();
			trigger_error('������ � ���� �� ����������, �� �������� �����'. Db::getLastError() , E_USER_ERROR );
		} else {
			$this->error .= ' ������ � ���� �� ����������, ��� �������������� id \n'. Db::getLastError();
			trigger_error('������ � ���� �� ����������, ��� �������������� id'. Db::getLastError() , E_USER_ERROR );
		}
		return false;
	}

	/**
	 * ������ ���������� �� �������� ����������
	 * @return bool
	 */
	private function _parser_html() {
		if ( $this->html && $this->html->innertext != '' && count( $this->html->find( $this->quote ) ) ) {
			return true;
		}
		$this->error .= ' ������ � ���� �� ����������. ��� ��������, ���������������� �������� �������� \n'.
						Db::getLastError();
		trigger_error('������ � ���� �� ����������. ��� ��������, ���������������� �������� ��������' .
					  Db::getLastError(), E_USER_ERROR );
		return false;
	}

	/**
	 *  ������ ���� � ���� ������
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
	 * ������ ���� ���������� ���������� � ������� ��������
	 *
	 * @param $ok
	 */
	private function pick_upd( $ok ) {
		$data = [
				'suss' => $ok,
				'time' => $this->time,
				'date' => date( 'Y-m-d H:i:s' ), // ������: 2014-07-14 14:31:58
				'err'  => $this->error
		];

		Db::where( 'id', $this->id_pick );
		Db::update( 'pick', $data );
	}

	/**
	 *  ������� ���� ��������� ������ �� ����
	 */
	public function randCitata() {

		$rand = Db::orderBy( 'RAND()' )->get( 'citata', '1', 'cit, avtor' ); // "SELECT cit,avtor FROM citata ORDER BY RAND() LIMIT 1"
		if ( count($rand) > 0 ) {
			$this->citata = urldecode( $rand [0]['cit'] );
			$this->avtor  = urldecode( $rand [0]['avtor'] );
		} else {
			$this->citata = '��� ������ �� ���������� � ������ 35-�� ������.'; // ������ �� ���������.
			$this->avtor  = 'W. Eugene Smith';
		}
	}

	function __destruct() {

		if ( $this->html ) {
			$this->html->clear(); // ��������� �� �����
			unset( $this->html );
		}
	}

}