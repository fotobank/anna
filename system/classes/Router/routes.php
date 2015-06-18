<?php
return [
	'/'         => [
		'controller' => 'Index',
		'method'     => 'index'
	],
	'index'     => [
		'controller' => 'Index',
		'method'     => 'index'
	],
	'about'     => [
		'controller' => 'About',
		'method'     => 'about'
	],
	'portfolio' => [
		'controller' => 'Portfolio',
		'method'     => 'portfolio'
	],
	'news'      => [
		'controller' => 'News',
		'method'     => 'news'
	],
	'services'  => [
		'controller' => 'Services',
		'method'     => 'services'
	],
	'comments'  => [
		'controller' => 'Comments',
		'method'     => 'comments'
	],
	/*'carousel/thumb'     => [
		'controller' => 'Carousel',
		'method'     => 'thumb'
	],
	'carousel/view'      => [
		'controller' => 'Carousel',
		'method'     => 'view'
	],*/
	'carousel'      => [
		'controller' => 'Carousel'
	],
	'error404'  => [
		'controller' => 'Error',
		'method'     => 'error404'
	]
];