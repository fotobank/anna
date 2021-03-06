<?php
use proxy\Db as Db;

include(__DIR__ .  '/../../../src/configs/define/config.php');

if(session_id() == ''){session_start();}
header( 'Content-type: text/html; charset=windows-1251' );

/**
 * Class EditBody
 */
class EditBody{

	private $data;
	protected $admin = false;

	/**
	 * @param $par
	 */
	public function __construct($par){

		$this->admin = if_admin(true);

		if(is_array($par))
			$this->data = $par;
	}

	/**
	 * ������ ���������� � ��������
	 * @return bool
	 */
	protected function actions() {
		return ($this->admin)?('<div class="actions">
							<a href="#" title="�������� ��� �������� ����� ����." class="edit">Edit</a>
							<a href="#" title="������� ������ �� ���� ������ ��� ����������� ��������������." class="delete">Delete</a>
							</div>'):false;
	}

	protected function add_block() {

	$block = '<ul class="block_wrap block_rounded">
							<li>
								<h4>� ��������</h4>
							</li>
							<li>
								<div class="focal-point border">
									<div><img src="" alt=""></div>
								</div>
							</li>
							<li>
								<p id="tab-1-1" class="edit-txt">������������ ������� � ���, ��� ��� ����� ������ �������� � ��������� ��������
								����� ������� �����. ������������ ������� ��� ������������ ����. ������� � ������ ������
								����� ����� ��������� ������� ������������ ��������� ��������, ����� ��������� ������������
								�� ���� ��������� �� ����� � ����� �����������, ������ ��������������� � � ������� � ������
								����. ��� ����, ��� ���������� ���������, ������� ������� � ��� ������� ���������������,
								������� ��� �������� ����������. �� ����� ���������� ������� � ��������� ��� �������������
								��� � ����������� ������.
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

		    $ul = '<ul class="list-title">';
			foreach($this->data as $row){
				$ul .= self::new_li_body($row);
			}
				$ul .= '</ul>';
			    $ul .=if_admin('<div id="addButton" class="button_img"><span>�������� ������</span></div>
								<div id="dialog-confirm" title="������� ������?"><span>������ ����� ������� �� ���� ������!</span></div>
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
				<a class="text" '. if_admin('title="������� ������ ��� ��������������."').'>'.$li_data['name_head'].'</a>
			<span id="'.$li_data['id'].'" class="fright"></span>'.
		if_admin('<div class="actions">
							<a href="#" title="�������� ��� �������� ����� ����." class="edit">Edit</a>
							<a href="#" title="������� ������ �� ���� ������ ��� ����������� ��������������." class="delete">Delete</a>
						</div>').
		'</li>';
	}

	

	/**
	 * @param $txt_err
	 *
	 * @throws Exception
	 */
	protected static function if_error($txt_err) {

		if(Db::getLastError() != '&nbsp;&nbsp;') {
			throw new Exception($txt_err.' '.Db::getLastError());
		}

	}



	/**
	 * @param $id
	 * @param $text
	 *
	 * @throws Exception
	 */
	public static function edit($id, $text){

		$text = self::esc($text);
		if(!$text) throw new Exception('������������ ����������� �����!');

		$value = [
			'name_head' => $text,
			'edit_id' => isset($_SESSION['id'])?$_SESSION['id']:0
		];
		Db::where('id', $id);
		Db::update('index_menu', $value);

		self::if_error('�� ���� �������� �����!');
	}
	

	/**
	 * @param $id
	 *
	 * @throws Exception
	 */
	public static function delete($id){

		Db::where('id', $id);
		Db::delete('index_menu');

		self::if_error('�� ���� ������� �����!');
	}
	
	/**
		����� ��������� ������� ����������, ����� ����������
	 *  ������������. ��������� �������� �������, �������
	 * �������� �������������� ������������ � ����� �������.
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
		if(!count($strVals)) throw new Exception('��� ������!');

		// �� ���������� �������� CASE SQL ��� ��������� ���������� ������� ���������:
		
		/*mysql_query("	UPDATE index_menu SET position = CASE id
						".implode ($strVals)."
						ELSE position
						END");*/

		Db::rawQuery('UPDATE index_menu SET position = CASE id '.implode ($strVals).' ELSE position END');

		self::if_error('������ ���������� �������!');
	}


	/**
	 * ����� createNew ��������� ������ �����,
	 * ����� � Databse � ������� ����� ��������� �� ��������
	 * ������� � ������� AJAX.
	 *
	 * @param $text
	 *
	 * @throws Exception
	 */
	public static function createNew($text) {

		$text = self::esc($text);
		if(!$text) throw new Exception('������������ ���� ������!');

//		$position = $db->rawQuery("SELECT MAX(position)+1 FROM index_menu");

		$position = Db::getOne ('index_menu', 'MAX(position)+1 as maxPosition');


		if(!$position['maxPosition']) $position['maxPosition'] = 1;

		$values = [
			'name_head' => $text,
			'position' => $position['maxPosition'],
			'edit_id' => isset($_SESSION['id'])?$_SESSION['id']:0

		];
		Db::insert('index_menu', $values);

		self::if_error('������ ������� �����!');

		echo (self::new_li_body(
							[
								'id'	=> Db::getInsertId(),
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