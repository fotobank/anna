<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:23
 */

// use Mustache\core\services as init;

include( __DIR__ . '/inc/config.php' ); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );


 $services = [
	 'content' => true,
	 'tabs' => true,
	 'data' => true,
	 'link' => [
			 [
				 'li_id' => 'head-151',
				 'li_class' => '',
				 'tab' => '#tab-1',
				 'name' => 'Фотограф:'
			 ],
			 [
				 'li_id' => 'head-152',
				 'li_class' => '',
				 'tab' => '#tab-2',
				 'name' => 'Изготовление фотокниг:'
			 ],
		 [
			 'li_id' => 'head-153',
			 'li_class' => '',
			 'tab' => '#tab-3',
			 'name' => 'Банкеты:'
		 ],
		 [
			 'li_id' => 'head-154',
			 'li_class' => '',
			 'tab' => '#tab-15',
			 'name' => 'Test:'
		 ]
	 ]
 ];

$tpl = $mustache->loadTemplate('services');



/*$services = new init\classServices([
	'edit' => false,
    'names' => true,
	'data' => true,
	'li_name_class' => 'class="selected"',
	'tab' => '#tab-1',
	'name' => 'Фотограф на свадьбу'
]);*/

echo $tpl->render($services);


?>
	<!--==============================footer================================-->
<?
include_once( __DIR__ . '/inc/footer.php' );
?>