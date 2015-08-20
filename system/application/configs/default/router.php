<?php
return [
	'index'     => [
		'controller' => 'Index',
		'method'     => 'index'
	],
	'index/id'     => [
		'controller' => 'Index',
		'method'     => 'id'
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
		'controller' => 'Portfolio',
		'method'     => 'portfolio'
//		'controller' => 'StubPage',
//		'method'     => 'stubPage'
	],
	'news'      => [
		'controller' => 'News',
		'method'     => 'news'
//		'controller' => 'StubPage',
//		'method'     => 'stubPage'
	],
	'services'  => [
		'controller' => 'Services',
		'method'     => 'services'
//		'controller' => 'StubPage',
//		'method'     => 'stubPage'
	],
	'comments'  => [
		'controller' => 'Comments',
		'method'     => 'comments'
//		'controller' => 'StubPage',
//		'method'     => 'stubPage'
	],
	'carousel'      => [
		'controller' => 'Carousel'

	],
	'404'  => [
		'controller' => 'Location',
		'method'     => 'error404'
	],
	'403'  => [
		'controller' => 'Location',
		'method'     => 'error403'
	],
	'stop'  => [
		'controller' => 'Location',
		'method'     => 'stop'
	],
	// ajax e-mail на странице блокировки
	'subscription_lock_page' => [
		'controller' => 'StubPage',
		'method'     => 'toEmail'
	],
	'stubPage' => [
		'controller' => 'StubPage',
		'method'     => 'stubPage'
	],
	'login' => [
		'controller' => 'Login',
		'method'     => 'userLogin'
	],
	'exit' => [
		'controller' => 'Login',
		'method'     => 'userExit'
	],
	'admin' => [
		'controller' => 'Admin',
		'method'     => 'admin'
	],
    'redirect' => [
        'controller' => 'Redirect',
        'method'     => 'redirect'
    ],
    'wm' => [
	    'controller' => 'Carousel',
	    'method'     => 'view'
    ],
	'th' => [
		'controller' => 'Carousel',
		'method'     => 'thumb'
	]
];