<?php
return [
	'/' => [ // Default controller
		'controller' => 'controllers\Index\Index',
		'method' => 'index',
		'number' => 'id',
		'param' => 'opt_param'
	],
	'index' => [
		'controller' => 'controllers\Index\Index',
		'method' => 'index',
		'number' => 'id',
		'param' => 'opt_param'
	],
	'admin/page' => [
		'controller' => 'Admin',
		'method' => 'page',
		'number' => 'id',
		'param' => 'opt_param'
	],
	'admin/authService' => [
		'controller' => 'Admin',
		'method' => 'authService',
		'number' => 'id',
		'param' => 'opt_param'
	],
	'admin' => [
		'controller' => 'Admin',
		'method' => 'index',
		'number' => 'id',
		'param' => 'opt_param'
	],
  'error404' => [
    'controller' => 'errors',
    'method' => 'error404'
  ]
];