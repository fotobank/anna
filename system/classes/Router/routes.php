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
		'controller' => 'Error',
		'method'     => 'error404'
	],
	'424'  => [
		'controller' => 'Error',
		'method'     => 'error424'
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
		'method'     => 'login'
	],
	'admin' => [
		'controller' => 'Admin',
		'method'     => 'admin'
	],
];