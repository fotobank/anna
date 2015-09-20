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

use modules\Models\Model\Model;
use lib\Config\Config;

	/**
	 * Class classServices
	 * @package mySpace
	 */
	class Services extends Model
	{
		private $edit;
		public $tabs;
		public $names;
		public $data;
		public $li_name_class;
		public $tab;
		public $name;


		/**
		 * @param \lib\Config\Config $config
		 *
		 * @throws \Exception
		 * @internal param $options param $dates* param $dates
		 */
		public function __construct(Config $config) {

			try
			{
				// настройка свойств класса
				$this->setOptions($config->getData('services'));
				// инициализация конструктора родительского класса
				parent::__construct($config);

				// установка исходных данных
//			$this->set_var( $options );
			}
			catch(\Exception $e)
			{
				throw $e;
			}
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