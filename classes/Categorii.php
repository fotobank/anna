<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 23:56
 */
use classes\pattern\Proxy\Db as Db;


/**
 * Class Categorii
 */
class Categorii {

	private $categorii; // запрос из базы
	private $menu; // меню

	/**
	 *
	 */
	public function __construct() {
		$this->categorii = Db::get('index_menu', NULL, [ 'id', 'name_head' ] );
	}

	/**
	 * @return string
	 */
	public function print_menu() {

		$menu = '';
		if(count($this->categorii)) {
			$menu .= '<ul class="list-title nav">';
			foreach($this->categorii as $key => $cat) {

				if(0 == $key) {
					$menu .= '<li class="selected">
					<a id="head-'.$cat['id'].'" href="#">'.$cat['name_head'].'</a></li>';
				} else {
					$menu .= '<li>
					<a id="head-'.$cat['id'].'" href="#">'.$cat['name_head'].'</a></li>';
				}


			}
			$menu .= '</ul>';
		}
		$this->menu = $menu;
		return $this->menu;
	}


	/**
	 *
	 */
	public function get_razdel() {

		Db::where('id_head','');
		$data_razdel = Db::getOne('spec_category', '');
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->print_menu();
	}

} 