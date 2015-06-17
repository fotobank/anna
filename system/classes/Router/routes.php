<?php
return [
	'/'                 => [ // Default controller
							 'controller' => 'Index',
							 'method'     => 'index'
	],
	'index'             => [
		'controller' => 'Index',
		'method'     => 'index'
	],
	'about'             => [
		'controller' => 'About',
		'method'     => 'about'
	],
	'portfolio'             => [
		'controller' => 'Portfolio',
		'method'     => 'portfolio'
	],
	'error404'          => [
		'controller' => 'Error',
		'method'     => 'error404'
	]
];