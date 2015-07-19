<?php
return [
	'index'     => [
		'controller' => 'Index',
		'method'     => 'index'
	],
    // заглушка
    'favicon.ico' => [
        'controller' => 'Index',
        'method'     => 'index'
    ],
	'about'     => [
		'controller' => 'About',
		'method'     => 'about'
	],
	'portfolio' => [
//		'controller' => 'Portfolio',
//		'method'     => 'portfolio'
		'controller' => 'StubPage',
		'method'     => 'stubPage'
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
	'carousel'      => [
		'controller' => 'Carousel'

	],
	'error404'  => [
		'controller' => 'Error',
		'method'     => 'error404'
	],
	'toEmail' => [
		'controller' => 'StubPage',
		'method'     => 'toEmail'
	],
	'stubPage' => [
		'controller' => 'StubPage',
		'method'     => 'stubPage'
	]
];