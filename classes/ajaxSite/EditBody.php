<?php

if(session_id() == '')
	session_start();


/**
 * Class ajaxSite_EditBody
 */
class ajaxSite_EditBody{

	private $data;
	protected $admin = false;

	public function __construct($par){

		$this->admin = if_admin(true);

		if(is_array($par))
			$this->data = $par;
	}

	/**
	 * кнопки добавления и удаления
	 * @return bool
	 */
	protected function actions() {
		return ($this->admin)?('<div class="actions">
							<a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
							<a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
							</div>'):false;
	}

	protected function add_block() {



	$block = '<ul class="block_wrap block_rounded">
							<li>
								<h4>О свадьбах</h4>
							</li>
							<li>
								<div class="focal-point border">
									<div><img src="" alt=""></div>
								</div>
							</li>
							<li>
								<p id="tab-1-1" class="edit-txt">Уникальность свадьбы в том, что это самый первый памятный и волнующий праздник
								новой молодой семьи. Незабываемое событие для возлюбленной пары. Которое в полном смысле
								этого слова старается сделать незабываемым свадебный фотограф, чтобы сохранить воспоминания
								об этом празднике на живых и ярких фотографиях, снятых профессионально и с любовью к своему
								делу. Для меня, как свадебного фотографа, снимать свадьбу – это большая ответственность,
								которую мне доверили молодожены. Во время фотосессии свадьбы я использую как постановочную
								так и репортажную съемку.
								</p>
							</li>
							<li>
								 <a href="http://www.aleks.od.ua">aleks.od.ua</a>
							</li>
						</ul>';



	}

	/**
	 * @return string
	 */
	public function __toString(){

		    $ul = '<ul class="list-categorii">';
			foreach($this->data as $row){
				$ul .= self::new_li_body($row);
			}
				$ul .= '</ul>';
			    $ul .=if_admin('<a id="addButton" class="support-hover" tabindex="1" title="Добавить раздел"><em>+</em></a>
								<div id="dialog-confirm" title="Удалить запись?"><span>Запись будет удалена из базы данных!</span></div>
							   ');

			return $ul;
			}

	/**
	 * @param $li_data
	 *
	 * @return string
	 */
	public static function new_li_body($li_data) {

		return '
			<li id="head-'.$li_data['id'].'">
				<a class="text" '. if_admin('title="Двойной щелчок для редактирования."').'>'.$li_data['name_head'].'</a>
			<span id="'.$li_data['id'].'" class="fright"></span>'.
		if_admin('<div class="actions">
							<a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
							<a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
						</div>').
		'</li>';
	}

	/**
	 * @return object
	 */
	protected static function db() {
		return Mysqli_Db::getInstance( Mysqli_Db::get_param());
	}

	/**
	 * @param $txt_err
	 *
	 * @throws Exception
	 */
	protected static function if_error($txt_err) {

		if(self::db()->getLastError() != '&nbsp;&nbsp;') {
			throw new Exception($txt_err." ".self::db()->getLastError());
		}

	}

	/**
		Нижеприведенные статические методы доступны
        непосредственно, без необходимости создания объекта.
	*/

	/**
	 * @param $id
	 * @param $text
	 *
	 * @throws Exception
	 */
	public static function edit($id, $text){

		$text = self::esc($text);
		if(!$text) throw new Exception("Неправильный обновляемый текст!");

		$value = array(
			'name_head' => $text,
			'red_id' => isset($_SESSION['id'])?$_SESSION['id']:0
		);
		self::db()->where("id", $id);
		self::db()->update("index_menu", $value);

		self::if_error("Не могу обновить пункт!");
	}
	

	/**
	 * @param $id
	 *
	 * @throws Exception
	 */
	public static function delete($id){

		self::db()->where("id", $id);
		self::db()->delete("index_menu");

		self::if_error("Не могу удалить пункт!");
	}
	
	/**
		Метод изменения порядка вызывается, когда изменяется
	 *  упорядочение. Принимает параметр массива, который
	 * содержит идентификаторы несделанного в новом порядке.
	*/
	/**
	 * @param $key_value
	 *
	 * @throws Exception
	 */
	public static function rearrange($key_value){

		$strVals = [];
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		if(!count($strVals)) throw new Exception("Нет данных!");

		// Мы используем оператор CASE SQL для массового обновления позиции заголовка:
		
		/*mysql_query("	UPDATE index_menu SET position = CASE id
						".implode ($strVals)."
						ELSE position
						END");*/

		self::db()->rawQuery("UPDATE index_menu SET position = CASE id ".implode ($strVals)." ELSE position END");

		self::if_error("Ошибка обновления позиции!");
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
	public static function createNew($text) {

		$text = self::esc($text);
		if(!$text) throw new Exception("Неправильный ввод данных!");

//		$position = $db->rawQuery("SELECT MAX(position)+1 FROM index_menu");

		$position = self::db()->getOne ("index_menu", "MAX(position)+1 as maxPosition");


		if(!$position['maxPosition']) $position['maxPosition'] = 1;

		$values = [
			'name_head' => $text,
			'position' => $position['maxPosition'],
			'red_id' => isset($_SESSION['id'])?$_SESSION['id']:0

		];
		self::db()->insert("index_menu", $values);

		self::if_error("Ошибка вставки главы!");

		echo (self::new_li_body(
							[
								'id'	=> self::db()->getInsertId(),
								'name_head'	=> $text
							]
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
	public static function esc($text){

		$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($text))));
		return $text;
	}
	
}