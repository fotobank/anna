<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 11.04.2015
 * Time: 13:27
 */

/**
 * Class Chris
 */
namespace Mustache\Core\Services {
	/**
	 * Class classServices
	 * @package mySpace
	 */
	class servicesPage {
		private $edit = false;
		public $tabs = false;
		public $names = false;
		public $data = false;
		public $li_name_class = false;
		public $tab = false;
		public $name = false;


		/**
		 * @param $dates
		 */
		public function __construct($dates) {
			$this->set_var( $dates ); // установка исходных данных
		}

		/**
		 * @param $dates
		 */
		private function set_var( $dates ) {
			if ( count( $dates ) ) {
				foreach ( $dates as $name => $data ) {
					$this->$name = $data;
				}
			}
		}

		/**
		 * @return int
		 */
		public function edit() {
			return $this->edit;
		}

		/**
		 * @return int
		 */
		public function content() {
			if($this->edit) {
				return false;
			} else {
				$this->tabs = true;
				return true;
			}
		}

	}
}