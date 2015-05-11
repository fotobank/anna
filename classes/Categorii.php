<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 23:56
 */

class Categorii {

	private $db = false; // экземпл€р подключени€ к базе данных
	private $categorii; // запрос из базы
	private $menu; // меню

	/**
	 *
	 */
	public function __construct() {
		$this->db = Db::getInstance(Db::get_param());
		$this->categorii = $this->db->get("index_menu", NULL, [ "id", "name_head" ] );
	}

	/**
	 * @return string
	 */
	public function print_menu() {

		$menu = "";
		if(count($this->categorii)) {
			$menu .= "<ul class='list-title nav'>";
			foreach($this->categorii as $key => $cat) {

				if(0 == $key) {
					$menu .= "<li class='selected'>
					<a id='head-".$cat['id']."' href='#'>".$cat['name_head']."</a></li>";
				} else {
					$menu .= "<li>
					<a id='head-".$cat['id']."' href='#'>".$cat['name_head']."</a></li>";
				}


			}
			$menu .= "</ul>";
		}
		$this->menu = $menu;
		return $this->menu;
	}


	/**
	 *
	 */
	public function get_razdel() {

		$this->db->where('id_head','');
		$data_razdel = $this->db->getOne('spec_category', '');
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->print_menu();
	}

} 