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
namespace modules\Models\Services;

use modules\Models\Base\Base;

	/**
	 * Class classServices
	 * @package mySpace
	 */
	class Services extends Base
	{
		private $edit;
		public $tabs;
		public $names;
		public $data;
		public $li_name_class;
		public $tab;
		public $name;


		/**
		 * @param $options
		 * param $dates
		 */
		public function __construct($options) {

			// настройка свойств класса
			$this->setOptions($options);
			// инициализация конструктора родительского класса
			parent::__construct();
			// лайтбокс в шапке

			// установка исходных данных
//			$this->set_var( $options );
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