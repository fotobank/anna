<?php
return [
	'/'                 => [ // Default controller
							 'controller' => 'controllers\Index\Index',
							 'method'     => 'index'
	],
	'index'             => [
		'controller' => 'controllers\Index\Index',
		'method'     => 'index'
	],
	'about'             => [
		'controller' => 'controllers\About\About',
		'method'     => 'about'
	],
	'error404'          => [
		'controller' => 'errors',
		'method'     => 'error404'
	]
];