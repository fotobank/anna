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
	'portfolio'             => [
		'controller' => 'controllers\Portfolio\Portfolio',
		'method'     => 'portfolio'
	],
	'error404'          => [
		'controller' => 'controllers\Error\Error',
		'method'     => 'error404'
	]
];