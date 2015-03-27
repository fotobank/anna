<?php

if ( session_id() == '' )
	session_start();


class ajaxSite_EditTitle {

	private $data;

	public function __construct( $par ) {

		if ( is_array( $par ) )
			$this->data = $par;
	}


	/**
	 * @return string
	 */
	public function __toString() {

		$ul = '<ul class="list-categorii">';
		foreach ( $this->data as $row ) {
			$ul .= self::new_li_title( $row );
		}
		$ul .= '</ul>';
		$ul .= if_admin( '<a id="addButton" class="support-hover" tabindex="1" title="Добавить раздел"><em>+</em></a>
		        				<div id="dialog-confirm" title="Удалить запись?"><span>Запись будет удалена из базы данных!</span></div>
		        			  ' );

		return $ul;
	}

	/**
	 * @param $li_data
	 *
	 * @return string
	 */
	public static function new_li_title( $li_data ) {

		return '
			<li id="head-' . $li_data['id'] . '">
				<a class="text" ' . if_admin( 'title="Двойной щелчок для редактирования."' ) . '>' . $li_data['name_head'] . '</a>
			<span id="' . $li_data['id'] . '" class="fright"></span>' .
		if_admin( '<div class="actions">
							<a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
							<a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
						</div>' ) .
		'</li>';
	}


	/**
	 * @return object
	 */
	protected static function db() {
		return Mysqli_Db::getInstance( Mysqli_Db::get_param() );
	}

	/**
	 * @param $txt_err
	 *
	 * @throws Exception
	 */
	protected static function if_error( $txt_err ) {

		if ( self::db()->getLastError() != '&nbsp;&nbsp;' ) {
			throw new Exception( $txt_err . " " . self::db()->getLastError() );
		}

	}

	/**
	 * Нижеприведенные статические методы доступны
	 * непосредственно, без необходимости создания объекта.
	 */

	/**
	 * @param $id
	 * @param $text
	 *
	 * @throws Exception
	 */
	public static function edit( $id, $text ) {

		$text = self::esc( $text );
		if ( !$text ) throw new Exception( "Неправильный обновляемый текст!" );

		$value = array(
			'name_head' => $text,
			'red_id'    => isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0
		);
		self::db()->where( "id", $id );
		self::db()->update( "menu_index_php", $value );

		self::if_error( "Не могу обновить пункт!" );
	}


	/**
	 * @param $id
	 *
	 * @throws Exception
	 */
	public static function delete( $id ) {

		self::db()->where( "id", $id );
		self::db()->delete( "menu_index_php" );

		self::if_error( "Не могу удалить пункт!" );
	}

	/**
	 * Метод изменения порядка вызывается, когда изменяется
	 *  упорядочение. Принимает параметр массива, который
	 * содержит идентификаторы несделанного в новом порядке.
	 */
	/**
	 * @param $key_value
	 *
	 * @throws Exception
	 */
	public static function rearrange( $key_value ) {

		$strVals = [ ];
		foreach ( $key_value as $k => $v ) {
			$strVals[] = 'WHEN ' . (int) $v . ' THEN ' . ( (int) $k + 1 ) . PHP_EOL;
		}
		if ( !count( $strVals ) ) throw new Exception( "Нет данных!" );

		// Мы используем оператор CASE SQL для массового обновления позиции заголовка:

		/*mysql_query("	UPDATE menu_index_php SET position = CASE id
						".implode ($strVals)."
						ELSE position
						END");*/

		self::db()->rawQuery( "UPDATE menu_index_php SET position = CASE id " . implode( $strVals ) . " ELSE position END" );

		self::if_error( "Ошибка обновления позиции!" );
	}


	/**
	 * Метод createNew принимает только текст,
	 * пишет в Databse и выводит новый заголовок на страницу
	 * обратно с помошью AJAX.
	 *
	 * @param $text
	 *
	 * @throws Exception
	 */
	public static function createNew( $text ) {

		$text = self::esc( $text );
		if ( !$text ) throw new Exception( "Неправильный ввод данных!" );

//		$position = $db->rawQuery("SELECT MAX(position)+1 FROM menu_index_php");

		$position = self::db()->getOne( "menu_index_php", "MAX(position)+1 as maxPosition" );


		if ( !$position['maxPosition'] ) $position['maxPosition'] = 1;

		$values = array(
			'name_head' => $text,
			'position'  => $position['maxPosition'],
			'red_id'    => isset( $_SESSION['id'] ) ? $_SESSION['id'] : 0

		);
		self::db()->insert( "menu_index_php", $values );

		self::if_error( "Ошибка вставки главы!" );

		echo( self::new_li_title(
			array(
				'id'        => self::db()->getInsertId(),
				'name_head' => $text
			)
		)
		);

		exit;
	}


	/**
	 * A helper method to sanitize a string:
	 *
	 * @param $text
	 *
	 * @return mixed|string
	 */
	public static function esc( $text ) {

		$text = cp1251( preg_replace( '/[\n\t]{1,}/i', '', nl2br( cleanInput( $text ) ) ) );
		return $text;
	}

}